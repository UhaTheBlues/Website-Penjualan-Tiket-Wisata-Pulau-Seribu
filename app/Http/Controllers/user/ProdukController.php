<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Rating;
use App\User;
use App\Categories;
use Illuminate\Support\Facades\DB;
class ProdukController extends Controller
{
    public function index()
    {
        //menampilkan data produk yang dijoin dengan table kategori
        //kemudian dikasih paginasi 9 data per halaman nya
        $kat = DB::table('categories')
                ->join('products','products.categories_id','=','categories.id')
                ->select(DB::raw('count(products.categories_id) as jumlah, categories.*'))
                ->groupBy('categories.id')
                ->get();
        $data = array(
            'produks' => DB::table('products')->orderBy('name', 'asc')->paginate(9),
            'user' => DB::table('users')->where('role','=','customer')->get(),
            'product' => DB::table('products')->get(),
            'rating' => DB::table('rating')->get(),
            'categories' => $kat
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



        return view('user.produk',$data);
    }

}
    public function detail($id)
    {
        //mengambil detail produk
        $data = array(

            'produk' => Product::findOrFail($id),
            // 'rating_id' => Rating::where('id_products',$id)->get(),

             'ratings' => DB::table('rating')->paginate(9),
            'rating_id' => DB::table('rating')
                            ->join('users','users.id','=','rating.id_users')
                            ->select('rating.*','users.name')
                            ->where('rating.id_products',$id)->get(),
            'rating' => DB::table('rating')->get(),
        );

         $paging = array (
            'produk' => Product::findOrFail($id),
            'page' => DB::table('rating')
                ->join('users', 'users.id', '=', 'rating.id_users')
                ->select('rating.*', 'users.name')
                ->where('rating.id_products', $id)->paginate(3),
        );


        // rating rata2
        // foreach ($data['produk'] as $row) {
        $jumlah_rating_produk = 0;
        $rata_rating_produk = 0;
        $a = 0;
        foreach ($data['rating'] as $key) {
            if ($data['produk']->id == $key->id_products) {
                $a++;
                $jumlah_rating_produk = $key->rating + $jumlah_rating_produk;
            }
        }
        if (! empty($jumlah_rating) OR $jumlah_rating_produk > 0) {
            $rata_rating_produk = $jumlah_rating_produk/$a;
        }
        // }
        $data['jumlah_rating_produk']   = $jumlah_rating_produk;
        $data['rata_rating_produk']     = $rata_rating_produk;
        // print_r($data['rating_id']);
        return view('user.produkdetail',$data, $paging);
    }

    public function cari(Request $request)
    {
        //mencari produk yang dicari user
        $prod  = Product::where('name','like','%' . $request->cari. '%')->paginate(9);
        $total = Product::where('name','like','%' . $request->cari. '%')->count(); 
        $data  = array(
            'produks' => $prod,
            'cari' => $request->cari,
            'total' => $total
        );
        return view('user.cariproduk',$data);

    }

}
