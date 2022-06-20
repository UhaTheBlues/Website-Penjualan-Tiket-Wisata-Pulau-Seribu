<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }
?>
<div class="col-sm-12">      	
<h1 class="text-center">Data user</h1>
<br><br>
<div class="row btntambah">
	<div class="col-sm-2">
		<button class="btn btn-primary" data-toggle="modal" data-target="#tambah_user">Tambah user</button>
	</div>
</div>
<table class="table table-hover table-bordered" id="dataku">
  <thead>
  	<tr>
  		<th>No</th>
  		<th>username</th>
  		<th>Option</th>

  	</tr>
  </thead>

  <tbody>
  	<?php 
  	$no=1;
  	$query = "SELECT * FROM users";
  	$result = mysqli_query($con, $query);
  	while ($row=mysqli_fetch_assoc($result)) : ?>
  	<tr>
  		<td><?=$no++; ?></td>
  		<td><?=$row['username'] ?></td>
  		<td>
    <a href="#" onclick="edit_user('<?=$row['id'] ?>')" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#user_edit">edit</a> | 
    <a onclick="hapus_user('<?=$row['id'] ?>')" href="#" class="btn btn-xs btn-danger">hapus</a>
  </td>
  	</tr>
  
  <?php endwhile; ?>
  </tbody>
</table>
</div>

<script>
	$(document).ready(function() {
	$("#dataku").dataTable({

	  	"order": [],
    	"columnDefs": [ {
	      "defaultContent": "-",
	      "targets"  : 'no-sort',
	      "targets": "_all",
	      "orderable": false,
	    }]

	});


});
</script>