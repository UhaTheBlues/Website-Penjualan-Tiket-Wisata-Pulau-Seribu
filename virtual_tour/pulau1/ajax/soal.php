<?php 
//session_start();
require "../koneksi.php";
// if (!isset($_SESSION["login"])) {
//   header("Location: ".base_url."login.php");
//   exit;
// }
?>
<div class="row">              
      <div class="col-sm-12 text-center">
      	<div class="alert alert-warning alert-dismissible text-center" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <strong>Aturan Main : </strong> Soal dan jawaban akan tampil secara acak. setiap jawaban benar maka anda mendapat point +4 dan setiap jawaban salah anda mendapat point -1. 
		  <!-- <br><small>*Swipe ke bawah untuk Memulai kembali kuis.*</small> -->
		</div>
		
		<h1>Score Anda : <div id="nilai">0</div></h1>

		<?php 
		
		function fisherYatesShuffle($arr) {
			for($i = count(array($arr)) - 1; $i > 0; $i--) {
			$r = rand(0, $i);
			$tmp = $arr[$i];
			$arr[$i] = $arr[$r];
			$arr[$r] = $tmp;
			}
		return $arr;
		}
	
		$no=1;
		$soal = mysqli_query($con,"SELECT * from tbl_soal ORDER BY RAND() LIMIT 5");
		
		while (fisherYatesShuffle($row = mysqli_fetch_array($soal))) : ?>
			<?php
			$jawaban = mysqli_query($con,"SELECT * from tbl_jawaban WHERE id_soal = '".$row[0]."' ORDER BY RAND()  "); ?>
			<form id="form_jawab_<?= $no; ?>" method="POST" action="ajax/hasil.php">
			<p><?= $no; ?>. <?= $row[1]; ?></p>
	
			<div class="kotak">
			<?php 
			$total = mysqli_num_rows($jawaban);
			while ($rowjwb=mysqli_fetch_assoc($jawaban)) : 
			
			if ($total== 4) {
				$abjad = 'A';

			}elseif($total== 3) {
				$abjad = 'B';

			}elseif($total== 2) {
				$abjad = 'C';

			}else{
				$abjad='D';
			}
			?>
				
				<div class="row">				  
				  <div class="col-lg-12">
				  	<input type="hidden" id="id" name="id" value="<?=$row['id'] ?>">
				  	<input type="hidden" name="sementara" value="0">
				    <div class="input-group">
				      <span class="input-group-addon"><input class="cursor" type="radio" id="jawaban_<?=$no; ?>" name="jawaban" value="<?=$rowjwb['pilihan_jawab'] ?>"></span>
				      <input type="text" class="form-control" value="<?=$abjad.'. '. $rowjwb['pilihan_jawab'] ?>" readonly>
				      
				    </div><!-- /input-group -->
				  </div><!-- /.col-lg-6 -->
				</div><!-- /.row -->

			<?php $total--; endwhile; ?>
			</div>

			<br>
				<button id="btn_pilih_<?= $no; ?>" class="btn btn-lg btn-success">Pilih</button>
			</form>			
			
			<script>		
				$("#form_jawab_<?php echo $no; ?>").submit(function(){
					
					var next = '<?php echo $no+1; ?>';
					if (! $("#jawaban_<?=$no; ?>:checked").val()) {
				       swal({
							  title: "Hello.. !",
							  text: "Pilih dulu jawabannya !",
							  imageUrl: 'assets/img/warn.png'
							});
				        return false;
					}else{
					    $.ajax({
							type: 'POST',
							url: $(this).attr('action'),
							data: $(this).serialize(),
							success: function(data) {
								var myarr = data.split('/');
								if (myarr[0]=='jawaban anda benar, anda dapat 4 point') {
									swal({
										  title: "Benar !",
										  text: myarr[0],
										  imageUrl: 'assets/img/up.png'
										});
								}else{
									swal({
										  title: "Salah !",
										  text: myarr[0],
										  imageUrl: 'assets/img/down.png'
										});
								}			        			
			        			$('#nilai').text(myarr[1]);					
			        			$('#form_jawab_<?php echo $no; ?>').hide();
			        			$('#form_jawab_'+ next).show();
									
								return false;
								}
						});
						return false;
					 }
				});
			</script>
		<?php 
		$no++;
		endwhile;

		for ($i=2; $i <= 10 ; $i++) {  ?>
		<script>$('#form_jawab_<?=$i; ?>').hide()</script>
		<?php } ?>
        <button id="btn_ulang_" onclick="mulai()" class="btn btn-lg btn-success">Ulang Kuis</button>
      </div>

</div>