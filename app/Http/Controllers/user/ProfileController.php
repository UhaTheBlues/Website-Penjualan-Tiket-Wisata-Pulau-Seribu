<?php

namespace App\Http\Controllers\user;
use Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class ProfileController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }


    public function detail() {
    
    $user = User::where('id', Auth::user()->id)->first();
        return view('user.profile', compact('user'));

    }

     public function update(Request $request)
    {
        $this->validate($request, [
            'password'  => 'confirmed',
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->no_tlp = $request->no_tlp;
        $user->save();
        
         if(!empty($request->password))
         {
             $user->password = Hash::make($request->password);
         }
        
         $user->update();

        return redirect('profile')->with('status','Berhasil Mengedit Data');
    }


}
