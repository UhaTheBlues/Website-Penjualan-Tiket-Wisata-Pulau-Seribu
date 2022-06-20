<?php

namespace App\Http\Controllers\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Detailorder;
use App\Rekening;
use App\Rating;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class OrderController extends Controller
{

    public function index()
    {
        //menampilkan semua data pesanan
        $user_id = \Auth::user()->id;
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->select('order.*','status_order.name')
                    ->where('order.status_order_id',1)
                    ->where('order.user_id',$user_id)->get();
        $dicek = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->select('order.*','status_order.name')
                    ->where('order.status_order_id','!=',1)
                    ->Where('order.status_order_id','!=',5)
                    ->Where('order.status_order_id','!=',6)
                    ->where('order.user_id',$user_id)->get();
        $histori = DB::table('order')
        ->join('status_order','status_order.id','=','order.status_order_id')
        ->select('order.*','status_order.name')
        ->where('order.status_order_id','!=',1)
        ->Where('order.status_order_id','!=',2)
        ->Where('order.status_order_id','!=',3)
        ->Where('order.status_order_id','!=',4)
        ->where('order.user_id',$user_id)->get();
        $data = array(
            'order' => $order,
            'dicek' => $dicek,
            'histori'=> $histori
        );
        return view('user.order.order',$data);
    }

    public function detail($id)
    {
        //function menampilkan detail order
        $detail_order = DB::table('detail_order')
        ->join('products','products.id','=','detail_order.product_id')
        ->join('order','order.id','=','detail_order.order_id')
        ->select('products.name as nama_produk','products.image','detail_order.*','products.price','order.*')
        ->where('detail_order.order_id',$id)
        ->get();
        $order = DB::table('order')
        ->join('users','users.id','=','order.user_id')
        ->join('status_order','status_order.id','=','order.status_order_id')
        ->select('order.*','users.name as nama_pelanggan','status_order.name as status')
        ->where('order.id',$id)
        ->first();
        $rating_table = DB::table('rating')
        ->get();
        foreach ($rating_table as $key) {
            $nilai_rating[$key->id_users][$key->id_products] = $key->rating;
        }
        $data = array(
        'detail' => $detail_order,
        'order'  => $order,
        'rating_table'  => $rating_table,
        'nilai_rating'  => $nilai_rating, 

        );
      
        return view('user.order.detail',$data);
    }

    public function sukses()
    {
        //menampilkan view terimakasih jika order berhasil dibuat
        return view('user.terimakasih');
    }

    public function kirimbukti($id,Request $request)
    {
        //mengupload bukti pembayaran
        $order = Order::findOrFail($id);
        if($request->file('bukti_pembayaran')){
            $file = $request->file('bukti_pembayaran')->store('buktibayar','public');

            $order->bukti_pembayaran = $file;
            $order->status_order_id  = 2;
            $order->notif  = "";
            $order->save();

        }
        if ($order->ongkir == NULL) {
            $ongkir = 0;
        }
        $total_semua = floatval($order->subtotal)+$ongkir;
        $email =  \Auth::user()->email;
        $name = \Auth::user()->name;
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'in-v3.mailjet.com'; // host
            $mail->SMTPAuth = true;
            $mail->Username = '1effcbbde9de9700118622c51d5fedae'; //username
            $mail->Password = '5a64d6fab0b5201cce94f4c7f8771717'; //password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587; //smtp port
           
            $mail->setFrom('pulaukuy@gmail.com', 'Pulau Kuy');
            $mail->addAddress($email, '');
          
            $mail->isHTML(true);
            $mail->Subject = 'INVOICE PEMBAYARAN PULAUKUY INVOICE#'.$order->invoice;
            // $mail->Body    = $this->load->view('login/template_email',$data,true);
            // $mail->Body    = "Test";
            $mail->Body    = "
            <!doctype html><html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'><head><title></title><meta http-equiv='X-UA-Compatible' content='IE=edge'><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'><style type='text/css'>#outlook a { padding:0; }
          body { margin:0;padding:0;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%; }
          table, td { border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt; }
          img { border:0;height:auto;line-height:100%; outline:none;text-decoration:none;-ms-interpolation-mode:bicubic; }
          p { display:block;margin:13px 0; }</style>


          <style type='text/css'>@media only screen and (min-width:480px) {
        .mj-column-per-100 { width:100% !important; max-width: 100%; }
      }</style><style type='text/css'>[owa] .mj-column-per-100 { width:100% !important; max-width: 100%; }</style><style type='text/css'>@media only screen and (max-width:480px) {
      table.mj-full-width-mobile { width: 100% !important; }
      td.mj-full-width-mobile { width: auto !important; }
    }</style></head><body style='background-color:#F4F4F4;'><div style='background-color:#F4F4F4;'><div style='margin:0px auto;max-width:600px;'><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='left' style='font-size:0px;padding:0px 0px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='margin: 10px 0;'></p></div></td></tr></table></div><div style='background:transparent url(http://go.mailjet.com/tplimg/mtrq/b/ox8s/mg1qn.png) top center / auto repeat;margin:0px auto;max-width:600px;'><div style='line-height:0;font-size:0;'><table align='center' background='http://go.mailjet.com/tplimg/mtrq/b/ox8s/mg1qn.png' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:transparent url(http://go.mailjet.com/tplimg/mtrq/b/ox8s/mg1qn.png) top center / auto repeat;width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='center' vertical-align='top' style='font-size:0px;padding:10px 25px;word-break:break-word;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'><tbody><tr><td style='width:200px;'></td></tr></tbody></table></td></tr></table></div></td></tr></tbody></table></div></div><div style='background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;'><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#ffffff;background-color:#ffffff;width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='left' vertical-align='top' style='font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='text-align: left; margin: 10px 0; margin-top: 10px;'><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Hai ".$name."</span></p><p style='text-align: left; margin: 10px 0; margin-bottom: 10px;'><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Kami telah menerima bukti pembayaran anda dan kami sedang memprosesnya. </span><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'> Mohon menunggu untuk informasi lebih lanjut, bila pembayaran telah tervalidasi kami akan mengirimkan email kembali</span></p>
      <span style='font-size:16px;text-align:left;color:#5e6977;font-family:Arial;line-height:13px;'>
      <br>
           
        </span>
    </div></td></tr><tr><td align='center' vertical-align='top' style='font-size:0px;padding:15px 30px;word-break:break-word;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:separate;line-height:100%;'><tr><td align='center' bgcolor='#41B8FF' role='presentation' style='border:none;border-radius:0px;cursor:auto;mso-padding-alt:10px 25px;background:#41B8FF;' valign='top'></td></tr></table></td></tr></table></div></td></tr></tbody></table></div>
     <div style='background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;'><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#ffffff;background-color:#ffffff;width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='left' style='font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='text-align: left; margin: 10px 0; margin-top: 10px;'>
        
        </p><p style='text-align: left; margin: 10px 0; margin-bottom: 10px;'><span style='font-size:16px;text-align:left;color:#5e6977;font-family:Arial;line-height:13px;'></span></p></div></td></tr><tr><td align='left' style='font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='text-align: left; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;'><span style='line-height:22px;font-size:18px;font-family:Arial;color:#5e6977;text-align:left;'>Dari Pulau Kuy </span></p></div></td></tr></table></div></td></tr></tbody></table></div></div></body></html>
            ";
            $mail->send();

            return redirect()->route('user.order');
        } catch (Exception $e) {
            return redirect()->route('user.order');
        }
        
    }
    
    public function pembayaran($id)
    {
        //menampilkan view pembayaran
        $data = array(
            'rekening' => Rekening::all(),
            'order' => Order::findOrFail($id)
        );
        return view('user.order.pembayaran',$data);
    }

    public function pesananditerima($id)
    {
        //function untuk menerima pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 5;
        $order->save();

        return redirect()->route('user.order');

    }

    public function pesanandibatalkan($id)
    {
        //function untuk membatalkan pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 6;
        $order->save();

        return redirect()->route('user.order');

    }

    public function simpan(Request $request)
    {
        //untuk menyimpan pesanan ke table order
        $cek_invoice = DB::table('order')->where('invoice',$request->invoice)->count();
        if($cek_invoice < 1){
            $userid = \Auth::user()->id;
            //jika pelanggan memilih metode cod maka insert data yang ini
        if($request->metode_pembayaran == 'cod'){
            Order::create([
                'invoice' => $request->invoice,
                'user_id' => $userid,
                'subtotal'=> $request->subtotal,
                'status_order_id' => 1,
                'metode_pembayaran' => $request->metode_pembayaran,
                'biaya_cod' => 10000,
                'no_hp' => $request->no_hp,
                'pesan' => $request->pesan
            ]);
        }else{
            //jika memilih transfer maka data yang ini
            Order::create([
                'invoice' => $request->invoice,
                'user_id' => $userid,
                'subtotal'=> $request->subtotal,
                'status_order_id' => 1,
                'metode_pembayaran' => $request->metode_pembayaran,
                'no_hp' => $request->no_hp,
                'pesan' => $request->pesan
            ]);
        }

        $order = DB::table('order')->where('invoice',$request->invoice)->first();
        
        $barang = DB::table('keranjang')->where('user_id',$userid)->get();
        //lalu masukan barang2 yang dibeli ke table detail order
        foreach($barang as $brg){
            Detailorder::create([
                'order_id' => $order->id,
                'product_id' => $brg->products_id,
                'qty' => $brg->qty,
            ]);
        }
        //lalu hapus data produk pada keranjang pembeli
        DB::table('keranjang')->where('user_id',$userid)->delete();
        return redirect()->route('user.order.sukses');
        }else{
            return redirect()->route('user.keranjang');
        }
        // dd($request);

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
    public function insertrating(Request $request)
    {
        Rating::create([
            'id_users' => $request->id_users,
            'id_products' => $request->id_products,
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        return redirect()->route('user.order.detail',$request->id_order)->with('status','Berhasil Menambah rating');;
    }



}
