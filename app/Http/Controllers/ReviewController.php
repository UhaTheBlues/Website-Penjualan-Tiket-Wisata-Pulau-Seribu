<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;

class ReviewController extends Controller
{
     //function menampilkan view tambah data
    public function tambah()
    {
        return view('user.order.detail');
    }

    public function store(Request $request)
    {
        //Simpan datab ke database    
        Review::create([

            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar

        ]);
        
        //lalu reireact ke route user.order.detail dengan mengirim flashdata(session) berhasil tambah data untuk manampilkan alert succes tambah data
        return redirect()->route('user.order.detail')->with('status','Berhasil Menambah rating');
    }

}
