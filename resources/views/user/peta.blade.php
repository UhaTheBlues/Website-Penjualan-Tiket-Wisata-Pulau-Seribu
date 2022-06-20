@extends('user.app')
@section('content')
<style type="text/css">
  .pulau-box::before,
  .pulau-box::after{
  content: '';
  clear: both;
  display: table;
  }
  .pulau-box.hide{
  display: none;
  }
  .pulau1{
    width:100%; 
    height: 800px;
  }
  .pulau2{
    width:100%; 
    height: 800px;
  }
  .pulau3{
    width:100%; 
    height: 800px;
  }
</style>
<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="home">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Virtual Tour</strong></div>

    </div>
    </div>
</div>  
 
<!-- <div class="site-section"> -->
    <div class="container">
   <div class="col-md-12">
  
<div class="border p-4 rounded mb-4">
     <div class="col">
    <h4 class="card-title">Virtual Tour</h4>
    <hr style="height:1px;border-width:2;color:#3e7ba2;background-color:#3e7ba2">
<center>
  <button class="btn btn-primary" onclick="showPulau1()">Pulau Kelor</button>
  <button class="btn btn-primary" onclick="showPulau2()">Pulau Onrust</button>
  <button class="btn btn-primary" onclick="showPulau3()">Pulau Cipir</button>
  <button class="btn btn-primary" onclick="location.href='https://pulaukuy360.site/virtual_tour/';">Tampilan Full Virtual Tour</button>
</center>
<iframe class="pulau1 pulau-box" src="http://localhost/pulau1/">
  <p>Your browser does not support iframes.</p>
</iframe>
<iframe class="pulau2 pulau-box hide" src="http://localhost/pulau2/">
  <p>Your browser does not support iframes.</p>
</iframe>
<iframe class="pulau3 pulau-box hide" src="http://localhost/pulau3/">
  <p>Your browser does not support iframes.</p>
</iframe>

<script>
const pulauBox = document.querySelector(".pulau-box");
const pulaU1 = document.querySelector(".pulau1");
const pulaU2 = document.querySelector(".pulau2");
const pulaU3 = document.querySelector(".pulau3");

function showPulau1(){
  //menyembunyikan
  pulaU2.classList.add("hide");
  pulaU3.classList.add("hide");
  //menampilkan
  pulaU1.classList.remove("hide");
}
function showPulau2(){
  //menyembunyikan
  pulaU1.classList.add("hide");
  pulaU3.classList.add("hide");
  //menampilkan
  pulaU2.classList.remove("hide");
}
function showPulau3(){
  //menyembunyikan
  pulaU1.classList.add("hide");
  pulaU2.classList.add("hide");
  //menampilkan
  pulaU3.classList.remove("hide");
}
</script>

</div>
</div>
</div>
</div>
</div>
 </div>
    </div>
</div>
@endsection