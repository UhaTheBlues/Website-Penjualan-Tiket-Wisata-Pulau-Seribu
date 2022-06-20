@extends('user.app')
@section('content')

<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="home">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Edit Profile</strong></div>
    </div>
    </div>
</div>  


<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row mb-3">
                      <div class="col">
                      <h4 class="card-title">Edit Produk</h4>
                      </div>
                      <div class="col text-right">
                      <a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-primary">Kembali</a>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <form method="POST" action="{{ route('user.editProfile')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                <label for="exampleInputUsername1">Nama </label>
                                <input required type="text" class="form-control" name="name" value="{{ $user->name }}">
                                </div>
                                <div class="form-group">
                                <label for="exampleInputUsername1">Email</label>
                                <input required type="number" class="form-control" name="weigth" value="{{ $user->email}}">
                                </div>
                                <div class="form-group">
                                <label for="exampleInputUsername1">Jenis Kelamin</label>
                                <input required type="number" class="form-control" name="price" value="{{ $user->jenis_kelamin}}">
                                </div>
                                <div class="form-group">
                                <label for="exampleInputUsername1">No Telepon</label>
                                <input required type="number" class="form-control" name="stok" value="{{ $product->no_tlp}}">
                                </div>
                               
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success text-right">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
          
@endsection