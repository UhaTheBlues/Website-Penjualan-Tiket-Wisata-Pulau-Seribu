@extends('user.app')
@section('content')
<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0"><a href="home">Home</a>  <span class="mx-2 mb-0">/</span> <strong class="text-black">Paket Wisata</strong></div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">

    <div class="row mb-5">
        <div class="col-md-9 order-2">

            <div class="row mb-5">
                @foreach($produks as $produk)
                    <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                        <div class="block-4 text-center border">
                            <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">
                                <img src="{{ asset('public/' . $produk->image) }}" alt="Image placeholder" class="img-fluid" width="100%" style="height:200px">
                            </a>
                            <div class="block-4-text p-4">
                                {{ round($rata_rating_produk[$produk->id],1) }} <i class="fa fa-star" style="color: #eba83a"></i>
                            <h3><a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">{{ $produk->name }}</a></h3>
                            <p class="mb-0">RP {{ $produk->price }}</p>
                            <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}" class="btn btn-primary mt-2">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pull-right" data-aos="fade-up" >
                Menampilkan
                {{ $produks->lastItem()}}
                dari
                {{ $produks->total()}}
            </div>
        
        <div class="row" data-aos="fade-up">
            <div class="col-md-12 text-right">
            <div class="site-block-27">
            {{ $produks->links() }}
            </div>
            </div>
        </div>
        </div>

        <div class="col-md-3 order-1 mb-5 mb-md-0">
        <div class="border p-4 rounded mb-4">
            <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
            <ul class="list-unstyled mb-0">
            @foreach($categories as $categori)
            <li class="mb-1"><a href="{{ route('user.kategori',['id' => $categori->id]) }}" class="d-flex"><span>{{ $categori->name }}</span> <span class="text-black ml-auto">( {{ $categori->jumlah }} )</span></a>
            </li>
            @endforeach
            </ul>
        </div>
        </div>

    </div>
    
    </div>
</div>
@endsection