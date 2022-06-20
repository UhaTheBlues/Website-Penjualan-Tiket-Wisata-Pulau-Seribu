<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class MetodeController extends Controller
{
    public function index()
    {
        //data
        $data = array(
            'user' => DB::table('users')->where('role','=','customer')->get(),
            'product' => DB::table('products')->get(),
            'rating' => DB::table('rating')->get(),
        );
        // identification rating
        foreach ($data['rating'] as $key) {
            $rating[$key->id_users][$key->id_products] = $key->rating;
        }
        foreach ($data['product'] as $row) {
            foreach ($data['user'] as $value) {
                if (empty($rating[$value->id][$row->id])) {
                    $rating[$value->id][$row->id] = 0;
                }
            }
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
        return view('admin.metode.index',$data);
    }
}
