@extends('user.app')
@section('content')
<style type="text/css" scoped>
  .rating {
  display: inline-block;
  position: relative;
  height: 50px;
  line-height: 50px;
  font-size: 50px;
}

.rating label {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  cursor: pointer;
}

.rating label:last-child {
  position: static;
}

.rating label:nth-child(1) {
  z-index: 5;
}

.rating label:nth-child(2) {
  z-index: 4;
}

.rating label:nth-child(3) {
  z-index: 3;
}

.rating label:nth-child(4) {
  z-index: 2;
}

.rating label:nth-child(5) {
  z-index: 1;
}

.rating label input {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
}

.rating label .icon {
  float: left;
  color: transparent;
}

.rating label:last-child .icon {
  color: #000;
}

.rating:not(:hover) label input:checked ~ .icon,
.rating:hover label:hover input ~ .icon {
  color: #fb9300;
}

.rating label input:focus:not(:checked) ~ .icon:last-child {
  color: #000;
  text-shadow: 0 0 5px #fb9300;
}                    
</style>
<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><strong class="text-black">Home</strong><span class="mx-2 mb-0">/</span> <strong class="text-black">Order</strong></div>
    </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
    <div class="row mb-3">
        <div class="col-md-12 text-center">
            <h2 class="display-5">Detail Pesanan Anda</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">  
                <div class="card-body">
                <div class="row">
                <div class="col-md-8">
                    <table>
                        <tr>
                            <th>No Invoice</th>
                            <td>:</td>
                            <td>{{ $order->invoice }}</td>
                        </tr>
                        <tr>
                            <th>E-Mail</th>
                            <td>:</td>
                            <td>{{ $order->no_resi }}</td>
                        </tr>
                        <tr>
                            <th>Status Pesanan</th>
                            <td>:</td>
                            <td>{{ $order->status }}</td>
                        </tr>
                        <tr>
                            <th>Metode Pembayaran</th>
                            <td>:</td>
                            <td>
                            @if($order->metode_pembayaran == 'trf')
                                Transfer Bank
                            @else
                                COD
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total Pembayaran</th>
                            <td>:</td>
                            <td>Rp. {{ number_format($order->subtotal + $order->biaya_cod,2,',','.') }}</td>
                        </tr>
                        
                    </table>
                </div>
                <div class="col-md-4 text-right">
                    @if($order->status_order_id == 4)
                    <a href="{{ route('user.order.pesananditerima',['id' => $order->id]) }}" onclik="return confirm('Yakin ingin melanjutkan ?')" class="btn btn-primary">Email telah diterima</a><br>
                    <small>Untuk menyelesaikan pesanan silahkan mengisi review </small>
                    @endif
                </div>
                </div>
                </br>
                <div class="row mb-5 yuhu">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th class="product-thumbnail">Gambar</th>
                            <th class="product-name">Nama Produk</th>
                            <th class="product-price">Jumlah</th>
                            <th class="product-quantity" width="20%">Total</th>
                             @if($order->status_order_id == 5)
                             <th class="product-thumbnail">Review</th>
                              @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detail as $o)
                            <tr>
                                <td><img src="{{ asset('public/'.$o->image) }}" alt="" srcset="" width="50"></td>
                                <td>{{ $o->nama_produk }}</td>
                                <td>{{ $o->qty }}</td>
                                <td>{{ $o->qty * $o->price }}</td>
                            </tr>
                        @if($order->status_order_id == 5)
                    <td>
                        <!-- <?php echo  $order->user_id.$o->product_id?> -->
                        @if(empty($nilai_rating[$order->user_id][$o->product_id]))
                        <button id="myBtn" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter<?=$o->product_id?>">RATING</button>
                        @else
                        <button id="myBtn" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter<?=$o->product_id?>" disabled>TELAH DI RATING</button>
                        @endif
                        
                    </td>
                    <div class="modal fade" id="exampleModalCenter<?=$o->product_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Rating</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <form action="{{ route('user.order.insertrating') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <input type="hidden" name="id_users" id="inputId_users" class="form-control" value="<?=$o->user_id?>">
                        <input type="hidden" name="id_products" id="inputId_users" class="form-control" value="<?=$o->product_id?>">
                        <input type="hidden" name="id_order" id="inputId_users" class="form-control" value="<?=$o->id?>">
                          <div class="modal-body text-center">

                                <div class="rating">
                                  <label>
                                    <input type="radio" name="rating" value="1" />
                                    <span class="icon">★</span>
                                  </label>
                                  <label>
                                    <input type="radio" name="rating" value="2" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                  </label>
                                  <label>
                                    <input type="radio" name="rating" value="3" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>   
                                  </label>
                                  <label>
                                    <input type="radio" name="rating" value="4" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                  </label>
                                  <label>
                                    <input type="radio" name="rating" value="5" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                  </label>
                                </div>
                              <script type="text/javascript">
                                $(':radio').change(function() {
                                console.log('New star rating: ' + this.value);
                                });
                              </script>
                            </div>
                            <div class="container">
                            <div><input type="text" class="form-control" name="komentar" placeholder="isi komentar kamu"></div>
                            </div>
                          <div class="modal-footer">
                            
                            <button type="submit" class="btn btn-primary">kirim review</button>
                          </div>
                        </form>
                </div>
                </div>
            </div>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                            
                            
        </div>
    </div>
    
    </div>
</div>
@endsection