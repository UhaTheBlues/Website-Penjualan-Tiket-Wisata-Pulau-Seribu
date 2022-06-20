<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\User;
use Excel;
use PDF;
use App\Exports\TransaksiExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TransaksiController extends Controller
{
    public function index()
    {
        //ambil data order yang status nya 1 atau masih baru/belum melalukan pembayaran
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->join('users','users.id','=','order.user_id')
                    ->select('order.*','status_order.name','users.name as nama_pemesan')
                    ->where('order.status_order_id',1)
                    ->get();
        $data = array(
            'orderbaru' => $order
        );

        return view('admin.transaksi.index',$data);
    }

    public function detail($id)
    {
        //ambil data detail order sesuai id
        $detail_order = DB::table('detail_order')
                            ->join('products','products.id','=','detail_order.product_id')
                            ->join('order','order.id','=','detail_order.order_id')
                            ->select('products.name as nama_produk','products.image','detail_order.*','products.price','order.*')
                            ->where('detail_order.order_id',$id)
                            ->get();
        $order = DB::table('order')
                    ->join('users','users.id','=','order.user_id')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->select('order.*','users.name as nama_pelanggan','status_order.name as status','order.*','users.email as email_pelanggan')
                    ->where('order.id',$id)
                    ->first();
        $data = array(
            'detail' => $detail_order,
            'order'  => $order
        );
        $order = Order::findOrFail($id);
        $order->notif = 'cek';
        $order->save();
        return view('admin.transaksi.detail',$data);
    }

    public function perludicek()
    {
        //ambil data order yang status nya 2 atau belum di cek / sudah bayar
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->join('users','users.id','=','order.user_id')
                    ->select('order.*','status_order.name','users.name as nama_pemesan')
                    ->where('order.status_order_id',2)
                    ->get();
        $data = array(
            'orderbaru' => $order
        );

        return view('admin.transaksi.perludicek',$data);
    }

    public function perludikirim()
    {
        //ambil data order yang status nya 3 sudah dicek dan perlu dikirim(input no resi)
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->join('users','users.id','=','order.user_id')
                    ->select('order.*','status_order.name','users.name as nama_pemesan')
                    ->where('order.status_order_id',3)
                    ->get();
        $data = array(
            'orderbaru' => $order
        );

        return view('admin.transaksi.perludikirim',$data);
    }

    public function selesai()
    {
        //ambil data order yang status nya 5 barang sudah diterima pelangan
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->join('users','users.id','=','order.user_id')
                    ->select('order.*','status_order.name','users.name as nama_pemesan')
                    ->where('order.status_order_id',5)
                    ->get();
        $data = array(
            'orderbaru' => $order
        );

        return view('admin.transaksi.selesai',$data);
    }

    public function dibatalkan()
    {
        //ambil data order yang status nya 6 dibatalkan pelanngan
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->join('users','users.id','=','order.user_id')
                    ->select('order.*','status_order.name','users.name as nama_pemesan')
                    ->where('order.status_order_id',6)
                    ->get();
        $data = array(
            'orderbaru' => $order
        );

        return view('admin.transaksi.dibatalkan',$data);
    }

    public function dikirim()
    {
        //ambil data order yang status nya 4 atau sedang dikirim
        $order = DB::table('order')
                    ->join('status_order','status_order.id','=','order.status_order_id')
                    ->join('users','users.id','=','order.user_id')
                    ->select('order.*','status_order.name','users.name as nama_pemesan')
                    ->where('order.status_order_id',4)
                    ->get();
        $data = array(
            'orderbaru' => $order
        );

        return view('admin.transaksi.dikirim',$data);
    }

    public function konfirmasi($id)
    {
        //function ini untuk mengkonfirmasi bahwa pelanngan sudah melakukan pembayaran
        $order = Order::findOrFail($id);
        $order->status_order_id = 3;
        $order->save();

        $kurangistok = DB::table('detail_order')->where('order_id',$id)->get();
        foreach($kurangistok as $kurang){
            $ambilproduk = DB::table('products')->where('id',$kurang->product_id)->first();
            $ubahstok = $ambilproduk->stok - $kurang->qty;

            $update = DB::table('products')
                    ->where('id',$kurang->product_id)
                    ->update([
                        'stok' => $ubahstok
                    ]);
        }

        return redirect()->route('admin.transaksi.perludikirim')->with('status','Berhasil Mengonfirmasi Pembayaran Pesanan');
    }

    public function inputresi($id,Request $request)
    {
        //looping detail
        $detail_order = DB::table('detail_order')
        ->join('products','products.id','=','detail_order.product_id')
        ->join('order','order.id','=','detail_order.order_id')
        ->select('products.name as nama_produk','products.image','detail_order.*','products.price','order.*')
        ->where('detail_order.order_id',$id)
        ->get();
        //funtion untuk menginput no resi pesanan
        
        // return $detail_order;

        $order = Order::findOrFail($id);
        $order->no_resi = $request->no_resi;
        $order->status_order_id = 4;
        $order->save();

        $id_pemesan = $order->user_id;
        $user = User::findOrFail($id_pemesan);
        
        $total_semua = floatval($order->subtotal);
        
        $email =  $order->no_resi;
        $name = $user->name;
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
            $mail->Subject = 'BUKTI PEMBAYARAN PULAUKUY INVOICE#'.$order->invoice;
            // $mail->Body    = $this->load->view('login/template_email',$data,true);
            // $mail->Body    = "Test";
            $body    = "
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
    }</style></head><body style='background-color:#F4F4F4;'><div style='background-color:#F4F4F4;'><div style='margin:0px auto;max-width:600px;'><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0px 20px 0px;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='left' style='font-size:0px;padding:0px 0px 0px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='margin: 10px 0;'></p></div></td></tr></table></div><div style='background:transparent url(http://go.mailjet.com/tplimg/mtrq/b/ox8s/mg1qn.png) top center / auto repeat;margin:0px auto;max-width:600px;'><div style='line-height:0;font-size:0;'><table align='center' background='http://go.mailjet.com/tplimg/mtrq/b/ox8s/mg1qn.png' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:transparent url(http://go.mailjet.com/tplimg/mtrq/b/ox8s/mg1qn.png) top center / auto repeat;width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='center' vertical-align='top' style='font-size:0px;padding:10px 25px;word-break:break-word;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;border-spacing:0px;'><tbody><tr><td style='width:200px;'></td></tr></tbody></table></td></tr></table></div></td></tr></tbody></table></div></div><div style='background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;'><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#ffffff;background-color:#ffffff;width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='left' vertical-align='top' style='font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='text-align: left; margin: 10px 0; margin-top: 10px;'><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Hai ".$name."</span></p><p style='text-align: left; margin: 10px 0; margin-bottom: 10px;'><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Terima kasih telah melakukan transaksi di PulauKuy. Pembayaran anda telah sukses, harap datang ke tempat kami di muara kamal (mesjid nurul iman), silahkan tunjukan bukti pembayaran ini kepada petugas untuk pelayanan selanjutnya. Berikut detail pemesanan anda:</span><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Pembayaran anda telah sukses, perlihatkan bukti pembayaran ini pada guide yang bertugas di pelabukan kali adem.</span></p>
      <span style='font-size:16px;text-align:left;color:#5e6977;font-family:Arial;line-height:13px;'>
      <br>
            <table>
                <tr>
                  <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>No. Invoice</span></td>
                  <td> : </td>
                  <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'><b>".$order->invoice."</b></span></td>
                </tr>";


        foreach ($detail_order as $i => $data) {
            $body    .= "
                    <tr>
                      <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Nama Produk</span></td>
                      <td> : </td>
                      <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'><b>".$data->nama_produk."</b></span></td>
                    </tr>
                    <tr>
                      <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Qty</span></td>
                      <td> : </td>
                      <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'><b>".$data->qty."</b></span></td>
                    </tr>";

}
        $body    .= "

                <tr>
                  <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Subtotal</span></td>
                  <td> : </td>
                  <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'><b>Rp. ".number_format($order->subtotal)."</b></span></td>
                </tr>
               
                <tr>
                  <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'>Total</span></td>
                  <td> : </td>
                  <td><span style='font-size:18px;text-align:left;color:#5e6977;font-family:Arial;line-height:20px;'><b>Rp. ".number_format($total_semua)."</b></span></td>
                </tr>
            </table>
        </span>
    </div></td></tr><tr><td align='center' vertical-align='top' style='font-size:0px;padding:15px 30px;word-break:break-word;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:separate;line-height:100%;'><tr><td align='center' bgcolor='#41B8FF' role='presentation' style='border:none;border-radius:0px;cursor:auto;mso-padding-alt:10px 25px;background:#41B8FF;' valign='top'></td></tr></table></td></tr></table></div></td></tr></tbody></table></div>
     <div style='background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;'><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#ffffff;background-color:#ffffff;width:100%;'><tbody><tr><td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;'><div class='mj-column-per-100 mj-outlook-group-fix' style='font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'><tr><td align='left' style='font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='text-align: left; margin: 10px 0; margin-top: 10px;'>
        
        </p><p style='text-align: left; margin: 10px 0; margin-bottom: 10px;'><span style='font-size:16px;text-align:left;color:#5e6977;font-family:Arial;line-height:13px;'></span></p></div></td></tr><tr><td align='left' style='font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:0px;word-break:break-word;'><div style='font-family:Arial, sans-serif;font-size:13px;letter-spacing:normal;line-height:1;text-align:left;color:#000000;'><p style='text-align: left; margin: 10px 0; margin-top: 10px; margin-bottom: 10px;'><span style='line-height:22px;font-size:18px;font-family:Arial;color:#5e6977;text-align:left;'>Dari pulaukuy360.site </span></p></div></td></tr></table></div></td></tr></tbody></table></div></div></body></html>
            ";
            $mail->Body    = $body;
            $mail->send();

            return redirect()->route('admin.transaksi.perludikirim');
        } catch (Exception $e) {
            return redirect()->route('admin.transaksi.perludikirim');
        }




        return redirect()->route('admin.transaksi.perludikirim')->with('status','Berhasil Menginput Alamat Email');
    }


  public function exportExcel() 
    {
        return Excel::download(new TransaksiExport, 'Transaksi.xlsx');
  
    }
     public function exportPdf() 
     {
     $transaksi = DB::table('order')->where('order.status_order_id', '5')->get();
     $pdf =PDF::loadView('export.transaksipdf',['transaksi' => $transaksi]);
      return $pdf->download('transaksi.pdf');
}

}
