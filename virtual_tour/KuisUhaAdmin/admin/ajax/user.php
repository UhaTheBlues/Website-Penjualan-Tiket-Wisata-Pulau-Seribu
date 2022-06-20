<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }
?>
<div class="row"> 
	<div id="isitabeluser">
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
	</div>             
      

</div>


<!-- Modal edit user-->
<div class="modal fade" id="user_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Editing User</h4>
      </div>
      <div class="modal-body" id="isi_edituser">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saving_user()">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- end modal edituser -->


<!-- Modal tambah user-->
<div class="modal fade" id="tambah_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah User</h4>
      </div>
      <div class="modal-body">
      	<form id="form_tambah_user">
		  <div class="form-group">
		    <label for="username">Username</label>
        <input type="text" class="form-control" name="username" id="username">
		  </div>
		  <div class="form-group">
		    <label for="password">Password</label>
		    <input type="password" class="form-control" id="password" name="password" placeholder="isikan password">
		  </div>
      <button type="reset" class="btn btn-danger">Reset</button>
		</form>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saving_tambah_user()">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- end modal tambahuser -->

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

  function edit_user(id) {
    $('#isi_edituser').load('ajax/edit_user.php?id='+id);
  }


  function hapus_user(id) {
     swal({
      title: "Yakin akan menghapus User??",
      text: "Data akan hilang jika dihapus!",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel please!",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(isConfirm) {
      if (isConfirm) {
        $.ajax({
            url : "ajax/hapus_user.php",
            type: "POST",
            data: {id:id},
            success: function(data){
                swal("Deleted!", "Data has been deleted.", "success");
                $('#isitabeluser').load('ajax/isitabeluser.php');
            },
            error: function (jqXHR, textStatus, errorThrown){
                swal("Error !", "Error deleting data", "error");
            }
        });
        
      } else {
        swal("Cancelled", "oke.. data user masih aman :)", "error");
      }
    });
  }
 


  function saving_tambah_user(){
  	var url = "ajax/tambah_user.php";
        var formData = new FormData($('#form_tambah_user')[0]);
        if ($('#username').val()=='' || $('#password').val()=='') {
        	$('#tambah_user').modal('hide');
        	swal("Warning !", "Isi dulu bro semuanya", "error");
        }else{
	        	$.ajax({
	            url : url,
	            type: "POST",
	            data: formData,
	            contentType: false,
	            processData: false,
	            dataType: "JSON",
	            success: function(data)
	            {

	                if(data.status) //if success
	                {
	                    
	                    $('#tambah_user').modal('hide');
	                    swal("Success!", "Successfully Added", "success");
	                    $('#isitabeluser').load('ajax/isitabeluser.php');
	                                        
	                }

	               
	                
	            },
	            error: function (jqXHR, textStatus, errorThrown)
	            {
	                swal("Error !", "Error added data", "error");
	                
	            }
	            
	        });
        }
        
  }
 
  function saving_user() {
    var url = "ajax/ubah_user.php";
        var formData = new FormData($('#form_edit_user')[0]);
        $.ajax({
            url : url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(data)
            {

                if(data.status) //if success
                {
                    
                    $('#user_edit').modal('hide');
                    swal("Success!", "Successfully edit", "success");
                    $('#isitabeluser').load('ajax/isitabeluser.php');
                                        
                }

               
                
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                swal("Error !", "Error edit data", "error");
                
            }
            
        });

  }

</script>