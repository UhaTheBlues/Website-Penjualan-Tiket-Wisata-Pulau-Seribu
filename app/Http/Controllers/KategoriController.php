<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Categories;
use Illuminate\Support\Facades\DB;
class KategoriController extends Controller
{
    public function produkByKategori($id)
    {

         $kat = DB::table('categories')
                ->join('products','products.categories_id','=','categories.id')
                ->select(DB::raw('count(products.categories_id) as jumlah, categories.*'))
                ->groupBy('categories.id')
                ->get();
       //menampilkan data sesua kategori yang diminta user
        $data = array(
            'produks' => Product::where('categories_id',$id)->paginate(9),
            'user' => DB::table('users')->where('role','=','customer')->get(),
            'product' => DB::table('products')->get(),
            'rating' => DB::table('rating')->get(),
            'categories' => Categories::findOrFail($id),
            'categoris' => $kat


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

        // print_r($rating);
        $data['rating']         = $rating;
        $data['total_rating']   = $total_rating;
        $data['jumlah_rating']  = $jumlah_rating;
        $data['rata_rating']    = $rata_rating;
       
       
        $data['jumlah_rating_produk']   = $jumlah_rating_produk;
        $data['rata_rating_produk']     = $rata_rating_produk;



        return view('user.kategori',$data);
    }
}
}
