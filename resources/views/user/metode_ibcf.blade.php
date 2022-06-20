@extends('user.app')
@section('content')

<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="home">Home</a>  <span class="mx-2 mb-0">/</span> <strong class="text-black">Metode IBCF</strong></div>
    </div>
    </div>
</div>  
<div class="card-body">
                 
<div class="container">              
   <div class="col-md-12">
  
<div class="border p-4 rounded mb-4">
<div class="site-section">
    <div class="container">
    <div class="row">
        <div class="col-md-12">
                        <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Metode Item-Based Collaborative Filtering </h3>
              <nav aria-label="breadcrumb">
              </nav>
            </div>
            <div class="row">
              <div class="col-12 grid-margin">
                <!-- <div class="card">
                  <div class="card-body"> -->
                    
                <div class="table-responsive">
                <div class="table-responsive">
                    <h5 class="mt-3">List Rating</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <?php foreach ($product as $row) {?>
                                <th><?=$row->id ?></th>
                            <?php } ?>
                            <th>Rata - Rata</th>
                        </tr>
                        <?php foreach ($user as $key) {?>
                        <tr>
                            <td><?=$key->name ?></td>
                            <?php foreach ($product as $row) {?>
                                <td><?=$rating[$key->id][$row->id]; ?></td>
                            <?php } ?>
                            <td><?=$total_rating[$key->id] ?> / <?=$jumlah_rating[$key->id] ?> = <?=round($rata_rating[$key->id],2) ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                </div>

            <!-- Perhitungan similarity -->
                 <div class="table-responsive">
                 <div class="table-responsive">
                    <h5 class="mt-3">Adjusted Cosine</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>Product</th>
                            <?php foreach ($product as $row) {?>
                                <th><?=$row->id ?></th>
                            <?php } ?>
                        </tr>
                        <?php foreach ($product as $row) {?>
                        <tr>
                            <td><?=$row->id ?></td>
                            <?php foreach ($product as $value) {?>
                                <td><?=round($total_adjusted[$row->id][$value->id],6) ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
                </div>

            <!-- Perhitungan prediksi -->
            <div class="table-responsive">
                <div class="table-responsive">
                    <h5 class="mt-3">Simple Weighted Average</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th></th>
                            <?php foreach ($product as $row) {?>
                                <th><?=$row->id ?></th>
                            <?php } ?>
                        </tr>
                        <?php foreach ($user as $key) {?>
                        <tr>
                            <td><?=$key->name ?></td>
                            <?php foreach ($product as $row) {?>
                                <td><?=$w_average[$key->id][$row->id] ?></td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

           <!--  hasil rekomendasi  -->
                    <h5 class="mt-3">Result</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>User</th>
                            <th>Rekomendasi</th>
                        </tr>
                        <?php foreach ($user as $key) {?>
                        <tr>
                            <td><?=$key->name ?></td>
                            <td><?php 
                            if (! empty($result_id[$key->id])) {
                                for ($i=0; $i < count($result_id[$key->id]); $i++) { 
                                    echo $result_id[$key->id][$i];
                                    echo ",";
                                }
                            }
                            
                             ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    <h5 class="mt-3">Result Setelah Difilter</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>User</th>
                            <th>Rekomendasi</th>
                        </tr>
                        <?php foreach ($user as $key) {?>
                        <tr>
                            <td><?=$key->name ?></td>
                            <td><?php 
                            if (! empty($result_filter[$key->id])) {
                                for ($i=0; $i < count($result_filter[$key->id]); $i++) { 
                                    echo $result_filter[$key->id][$i];
                                    echo ",";
                                }
                            }
                            
                             ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        
        </div>
    </div>
    </div>
</div>
@endsection