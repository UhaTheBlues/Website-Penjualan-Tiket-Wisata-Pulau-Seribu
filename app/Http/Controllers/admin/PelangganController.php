<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index()
    {
        //ambil data pelanggan yang di join dengan table alamat, city,dan province
        $data = array(
            'pelanggan' => DB::table('users')
                        ->where('users.role','=','customer')
                        ->get()
        );
        // return $data;
        return view('admin.pelanggan.index',$data);
    }
}
