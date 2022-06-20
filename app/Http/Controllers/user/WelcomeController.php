<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Rating;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class WelcomeController extends Controller
{
    public function index()
    {
        //data
        $data = array(
            'user' => DB::table('users')->where('role','=','customer')->get(),
            'product' => DB::table('products')->get(),
            'rating' => DB::table('rating')->get(),
            'order' => DB::table('order')->get(),
            'detail_order' => DB::table('detail_order')->get(),
            'produks' => DB::table('products')->orderBy('name', 'asc')->limit(10)->get(),
        );
        // identification rating
        foreach ($data['rating'] as $key) {
            $rating[$key->id_users][$key->id_products] = $key->rating;
            // buat filter
            $rating_produk[$key->id_users][$key->id_products] = $key->id_products;
            $rating_users[$key->id_users][$key->id_products] = $key->id_users;
        }
        foreach ($data['product'] as $row) {
            foreach ($data['user'] as $value) {
                if (empty($rating[$value->id][$row->id])) {
                    $rating[$value->id][$row->id] = 0;

                }
            }
            // $categories_pro[$row->id] = $row->categories_id;
        }
        // hitung rata2
        foreach ($data['user'] as $key) {
            $total_rating[$key->id] = 0;
            $jumlah_rating[$key->id] = 0;
            foreach ($data['product'] as $row) {
                if(! empty($rating[$key->id][$row->id])){
                    $total_rating[$key->id] = $total_rating[$key->id] + $rating[$key->id][$row->id];
                    $jumlah_rating[$key->id]++;
                }
                // buat filter
                if (!empty($rating_produk[$key->id][$row->id]) and $rating_produk[$key->id][$row->id] == $row->id) {
                    $categories_pro[$key->id][$row->categories_id] = $row->categories_id;
                }
            }
            if ($total_rating[$key->id] != 0) {
                $rata_rating[$key->id] = $total_rating[$key->id]/$jumlah_rating[$key->id];
            }else{
                $rata_rating[$key->id] = 0;
            }
        }

        // adjusted cosine
        foreach ($data['product'] as $key) {
            foreach ($data['product'] as $row) {
                $total_adjusted[][] = 0;
                $first_line = 0;
                $akar_1 = 0;
                $akar_2 = 0;
                foreach ($data['user'] as $value) {
                    if($key->id != $row->id){
                        if ($rating[$value->id][$key->id] != 0 AND $rating[$value->id][$row->id] != 0) {
                            $first_line = (($rating[$value->id][$key->id]-$rata_rating[$value->id])*($rating[$value->id][$row->id] - $rata_rating[$value->id])) + $first_line;
                            $akar_1 = (pow(($rating[$value->id][$key->id]-$rata_rating[$value->id]), 2)) + $akar_1;
                            $akar_2 = (pow(($rating[$value->id][$row->id]-$rata_rating[$value->id]), 2)) + $akar_2;
                        }
                    }else{
                        $first_line = 0;
                        $akar_1 = 0;
                        $akar_2 = 0;
                    }
                }
                if ($first_line != 0) {
                    $total_adjusted[$key->id][$row->id] = $first_line/(sqrt($akar_1) * sqrt($akar_2));
                }else{
                    $total_adjusted[$key->id][$row->id] = 0;
                }
            }
        }
        if (! empty(\Auth::user()->id)) {
            $id_user = \Auth::user()->id;
        }else{
            $id_user = 0;
        }
        $result_id[] = "";
        $product_id[] = "";
        $result_total[] = "";
        $produk_choice[] = "";
        $result_filter[] = "";
        // simple weighted average
        foreach ($data['user'] as $key) {
            $a = 0;
            foreach ($data['product'] as $row) {
                $total_atas = 0;
                $total_bawah = 0;
                foreach ($data['product'] as $value) {
                    $total_atas = ($rating[$key->id][$value->id]*$total_adjusted[$row->id][$value->id]) + $total_atas;
                    $total_bawah = abs($total_adjusted[$row->id][$value->id]) + $total_bawah;
                }

                if ($total_atas != 0) {
                    $w_average[$key->id][$row->id] = $total_atas/$total_bawah;
                }else{
                    $w_average[$key->id][$row->id] = 0;
                }
                // result
                if ($w_average[$key->id][$row->id] > 0) { 
                    if($key->id == $id_user){
                        if (empty($rating_produk[$key->id][$row->id]) and !empty($categories_pro[$key->id][$row->categories_id])) {
                            if ($categories_pro[$key->id][$row->categories_id] == $row->categories_id) {
                                $result_filter[$row->id] = $row->id;
                            }
                        }
                        $result_id[$row->id] = $row->id;
                        $result_cat[$row->id] = $row->categories_id;
                        $result_total[$row->id] = $w_average[$key->id][$row->id];
                        $a++;
                    }
                }
            }
        }
        
        // rating rata2
        foreach ($data['product'] as $row) {
            $jumlah_rating_produk[$row->id] = 0;
            $rata_rating_produk[$row->id] = 0;
            $a = 0;
            foreach ($data['rating'] as $key) {
                if ($row->id == $key->id_products) {
                    $a++;
                    $jumlah_rating_produk[$row->id] = $key->rating + $jumlah_rating_produk[$row->id];
                }
            }
            if (! empty($jumlah_rating[$row->id]) OR $jumlah_rating_produk[$row->id] > 0) {
                $rata_rating_produk[$row->id] = $jumlah_rating_produk[$row->id]/$a;
            }

        }
        // foreach ($data['order'] as $key) {
        //     if ($key->user_id == $id_user) {
        //         $order_id[$key->id] = $key->id;
        //     }
        // }
        // foreach ($data['detail_order'] as $key) {
        //     if (!empty($order_id[$key->order_id]) and !empty($categories_pro[$key->product_id]) and $order_id[$key->order_id] == $key->order_id) {
        //         // cari kategori terkait
        //         $product_id[$categories_pro[$key->product_id]] = $categories_pro[$key->product_id];
        //         $produk_choice[$key->product_id] = $key->product_id;
        //     }
        // }
        // print_r($rating);
        $data['rating']         = $rating;
        $data['total_rating']   = $total_rating;
        $data['jumlah_rating']  = $jumlah_rating;
        $data['rata_rating']    = $rata_rating;
        $data['total_adjusted'] = $total_adjusted;
        $data['w_average']      = $w_average;
        $data['result_id']      = $result_id;
        $data['product_id']      = $product_id;
        $data['produk_choice']      = $produk_choice;
        $data['result_total']   = $result_total;
        $data['result_filter']   = $result_filter;
        $data['jumlah_rating_produk']   = $jumlah_rating_produk;
        $data['rata_rating_produk']     = $rata_rating_produk;
        // print_r($result_cat);
        // echo $userid = \Auth::user()->id;
        // print_r($produk_choice);
        return view('user.welcome',$data);
    }

    public function metode_ibcf()
    {
       //data
        $data = array(
            'user' => DB::table('users')->where('role','=','customer')->get(),
            'product' => DB::table('products')->get(),
            'rating' => DB::table('rating')->get(),
            'order' => DB::table('order')->get(),
            'detail_order' => DB::table('detail_order')->get(),
        );
        // identification rating
        foreach ($data['rating'] as $key) {
            $rating[$key->id_users][$key->id_products] = $key->rating;
            // buat filter
            $rating_produk[$key->id_users][$key->id_products] = $key->id_products;
            $rating_users[$key->id_users][$key->id_products] = $key->id_users;
        }
        // print_r($rating_produk);
        foreach ($data['product'] as $row) {
            foreach ($data['user'] as $value) {
                if (empty($rating[$value->id][$row->id])) {
                    $rating[$value->id][$row->id] = 0;
                }
            }
            // $categories_pro[$row->id] = $row->categories_id;

        }
        // hitung rata2
        foreach ($data['user'] as $key) {
            $total_rating[$key->id] = 0;
            $jumlah_rating[$key->id] = 0;
            foreach ($data['product'] as $row) {
                if(! empty($rating[$key->id][$row->id])){
                    $total_rating[$key->id] = $total_rating[$key->id] + $rating[$key->id][$row->id];
                    $jumlah_rating[$key->id]++;
                }
                // buat filter
                if (!empty($rating_produk[$key->id][$row->id]) and $rating_produk[$key->id][$row->id] == $row->id) {
                    $categories_pro[$key->id][$row->categories_id] = $row->categories_id;
                }
            }
            if ($total_rating[$key->id] != 0) {
                $rata_rating[$key->id] = $total_rating[$key->id]/$jumlah_rating[$key->id];
            }else{
                $rata_rating[$key->id] = 0;
            }
        }

        // adjusted cosine
        foreach ($data['product'] as $key) {
            foreach ($data['product'] as $row) {
                $total_adjusted[][] = 0;
                $first_line = 0;
                $akar_1 = 0;
                $akar_2 = 0;
                foreach ($data['user'] as $value) {
                    if($key->id != $row->id){
                        if ($rating[$value->id][$key->id] != 0 AND $rating[$value->id][$row->id] != 0) {
                            $first_line = (($rating[$value->id][$key->id]-$rata_rating[$value->id])*($rating[$value->id][$row->id] - $rata_rating[$value->id])) + $first_line;
                            $akar_1 = (pow(($rating[$value->id][$key->id]-$rata_rating[$value->id]), 2)) + $akar_1;
                            $akar_2 = (pow(($rating[$value->id][$row->id]-$rata_rating[$value->id]), 2)) + $akar_2;
                        }
                    }else{
                        $first_line = 0;
                        $akar_1 = 0;
                        $akar_2 = 0;
                    }
                }
                if ($first_line != 0) {
                    $total_adjusted[$key->id][$row->id] = $first_line/(sqrt($akar_1) * sqrt($akar_2));
                }else{
                    $total_adjusted[$key->id][$row->id] = 0;
                }
            }
        }

        // simple weighted average
        foreach ($data['user'] as $key) {
            foreach ($data['product'] as $row) {
                if (!empty($rating_produk[$key->id][$row->id]) and $rating_produk[$key->id][$row->id] == $row->id) {
                    $kategori_id[$key->id][$row->id] = $row->categories_id;
                }
            }
            $a = 0;
            $b = 0;
            foreach ($data['product'] as $row) {
                $total_atas = 0;
                $total_bawah = 0;
                foreach ($data['product'] as $value) {
                    $total_atas = ($rating[$key->id][$value->id]*$total_adjusted[$row->id][$value->id]) + $total_atas;
                    $total_bawah = abs($total_adjusted[$row->id][$value->id]) + $total_bawah;
                }

                if ($total_atas != 0) {
                    $w_average[$key->id][$row->id] = $total_atas/$total_bawah;
                }else{
                    $w_average[$key->id][$row->id] = 0;
                }
                // result
                if ($w_average[$key->id][$row->id] > 0) {
                    if (empty($rating_produk[$key->id][$row->id]) and !empty($categories_pro[$key->id][$row->categories_id])) {
                        if ($categories_pro[$key->id][$row->categories_id] == $row->categories_id) {
                            $result_filter[$key->id][$b] = $row->id;
                            $b++;
                        }
                    }
                    $result_id[$key->id][$a] = $row->id;
                    $result_total[$key->id][$a] = $w_average[$key->id][$row->id];
                    $a++;
                }
            }

        }
        
        // print_r($rating);
        $data['rating']         = $rating;
        $data['total_rating']   = $total_rating;
        $data['jumlah_rating']  = $jumlah_rating;
        $data['rata_rating']    = $rata_rating;
        $data['total_adjusted'] = $total_adjusted;
        $data['w_average']      = $w_average;
        $data['result_id']      = $result_id;
        $data['result_total']   = $result_total;
        $data['result_filter']   = $result_filter;
        // $data['product_id']      = $product_id;
        // $data['produk_choice']      = $produk_choice;
        // print_r($produk_choice);
        return view('user.metode_ibcf',$data);
    }
}
