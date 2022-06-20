@extends('user.app')
@section('content')
<div class="site-blocks-cover" style="background-image: url({{ asset('public/images/pulauseribu.jpg') }});" data-aos="fade">
    <div class="container">
    <div class="row align-items-start align-items-md-center justify-content-end">
        <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
        <h1 class="mb-2">Ayo berwisata bersama kami </h1>
        <div class="intro-text text-center text-md-left">
            <!-- <p class="mb-4">Jangan panik ayo piknik </p> -->
            <p>
            
            <a href="{{ route('user.produk') }}" class="btn btn-sm btn-primary">Cek Sekarang > </a>
            </p>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="site-section site-section-sm site-blocks-1">
    <div class="container">
    <div class="row">
        <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="">
        <div class="icon mr-4 align-self-start">
            <span class="icon-truck"></span>
        </div>
        <div class="text">
            <h2 class="text-uppercase">Pemesanan</h2>
            <p>Pemesanan dapat dilakukan secara online dan terjamin</p>
        </div>
        </div>
        <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="icon mr-4 align-self-start">
            <span class="icon-star"></span>
        </div>
        <div class="text">
            <h2 class="text-uppercase">Kualitas Oke</h2>
            <p>Kualitas pemandangan yang oke, pantang pulang sebelum senang.</p>
        </div>
        </div>
        <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="200">
        <div class="icon mr-4 align-self-start">
            <span class="icon-security"></span>
        </div>
        <div class="text">
            <h2 class="text-uppercase">Keamanan</h2>
            <p>Kami menjamin keamanan dengan asuransi perjalanan untuk anda.</p>
        </div>
        </div>
    </div>
    </div>
</div>
@if (Route::has('login'))
@auth
<div class="site-section block-3 site-blocks-2 bg-light"  data-aos="fade-up">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 site-section-heading text-center pt-4">
        <h2>Rekomendasi Produk</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="nonloop-block-3 owl-carousel" >
            <?php $a=0;foreach ($product as $produk) { if(! empty($result_filter[$produk->id])){?>
            <div class="item">
            <div class="block-4 text-center">
                <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">
                <figure class="block-4-image">
                <img src="{{ asset('public/'.$produk->image) }}" alt="Image placeholder" class="img-fluid" width="100%" style="height:300px">
                </figure>
                </a>
                <div class="block-4-text p-4">
                <h3><a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">{{ $produk->name }}</a></h3>
               <!--  <p class="mb-0">{{ $produk->price }}</p> -->
                <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}" class="btn btn-primary mt-2">Detail</a>
                </div>
            </div>
            </div>
            <?php  $a++;}}?>
            <?php if($a == 0){ ?>
              <p><i>Belum Ada Rekomendasi</i></p>
            <?php } ?>
        </div>
        </div>
    </div>
    </div>
</div>
@endauth
@endif
<div class="site-section block-3 site-blocks-2 bg-light"  data-aos="fade-up">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 site-section-heading text-center pt-4">
        <h2>Paket Wisata</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="nonloop-block-3 owl-carousel" >
            @foreach($produks as $produk)
            <div class="item">
            <div class="block-4 text-center">
                <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">
                <figure class="block-4-image">
                <img src="{{ asset('public/'.$produk->image) }}" alt="Image placeholder" class="img-fluid" width="100%" style="height:300px">
                </figure>
                </a>
                <div class="block-4-text p-4">

                    {{ round($rata_rating_produk[$produk->id],1) }} <i class="fa fa-star" style="color: #eba83a"></i>
                    <script src="https://use.fontawesome.com/29d45e997e.js"></script>
                <h3><a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">{{ $produk->name }}</a></h3>
               <!--  <p class="mb-0">{{ $produk->price }}</p> -->
                <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}" class="btn btn-primary mt-2">Detail</a>
                </div>
            </div>
            </div>
            @endforeach
        </div>
        </div>
    </div>
    </div>
</div>
    @endsection