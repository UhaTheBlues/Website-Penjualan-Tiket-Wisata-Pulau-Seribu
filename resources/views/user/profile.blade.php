@extends('user.app')
@section('content')

<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="home">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Profile</strong></div>
    </div>
    </div>
</div>  
 <div class="card-body">
                    <div class="row mb-3">
                     
                      
                    </div>

<!-- <div class="site-section"> -->
    <div class="container">
   <div class="col-md-12">

<div class="border p-4 rounded mb-4">
	<div class="row">
        <h4 class="card-title">Profile</h4> <br><br><br>
        <div class="col text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#btnEdit">
            Edit
            </button>
        </div>
    </div>
    <div class="col">
    <div class= "text-black ">
		<p> Nama 		: {{$user->name}} </p>
		<p> Email 	: {{$user->email}} </p>
        <p> Jenis Kelamin   : {{$user->jenis_kelamin}} </p>
        <p> No Telepon   : {{$user->no_tlp}} </p>
	   </div>
</div>
</div>
</div>
</div>
</div>
</div>
 <!-- Modal -->
<div class="modal fade" id="btnEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body">
        <form class="pt-3" method="POST" action="{{ route('user.profile.update',['id' => $user->id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nama:</label>
            <input required type="text" class="form-control" name="name" value="{{ $user->name}}">
        </div>
        <div class="form-group">
            <label for="message-text" class="col-form-label">Email:</label>
            <input required id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="email" value="{{ $user->email}}">
        </div>
        <div class="form-group">
            <label for="message-text" class="col-form-label">Jenis Kelamin:</label>
            <select required class="form-control" name="jenis_kelamin" value="{{ $user->jenis_kelamin}}">
                <option value="pria">Pria</option>
                <option value="wanita">Wanita</option>
            </select>
        </div>
        <div class="form-group">
            <label for="message-text" class="col-form-label">No Telepon:</label>
            <input required type="number" class="form-control" name="no_tlp" value="{{ $user->no_tlp}}">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

 </div>

    </div>
</div>
@endsection