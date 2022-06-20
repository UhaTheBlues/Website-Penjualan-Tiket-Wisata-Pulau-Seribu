<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
class CheckoutController extends Controller
{
    public function index()
    {
        //ambil session user id
        $id_user = \Auth::user()->id;
        //ambil produk apa saja yang akan dibeli user dari table keranjang
    
        $keranjangs = DB::table('keranjang')
                            ->join('users','users.id','=','keranjang.user_id')
                            ->join('products','products.id','=','keranjang.products_id')
                            ->select('products.name as nama_produk','products.image','products.tanggal','users.name','keranjang.*','products.price','users.email as email_pelanggan')
                            ->where('keranjang.user_id','=',$id_user)
                            ->get();


                            foreach ($keranjangs as $kr => $data) {
                                $products  = Product::find ($data->products_id); 
                                if ($data->qty > $products->stok) {
                                return redirect()->route('user.keranjang')->with('gagal','Jumlah stok tidak cukup');
                                }
                            
                            }


        $data = [
            'invoice' => 'ALV'.Date('Ymdhi'),
            'keranjangs' => $keranjangs,
        
        ];
        return view('user.checkout',$data);
    }
}
