@extends('admin.layout.app')
@section('content')
<div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                  <i class="mdi mdi-home"></i>
                </span> Metode Item-Based Collaborative Filtering </h3>
              <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h5 class="mt-3">List Rating</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
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
                    <h5 class="mt-3">Simple Weighted Average</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
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
                  </div>
                </div>
              </div>
            </div>
          </div>
          
@endsection
