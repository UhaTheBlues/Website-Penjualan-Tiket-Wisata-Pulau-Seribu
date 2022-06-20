<?php
require "koneksi.php";
?> 
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
		<title>Pulau Onrust</title>
		<meta name="viewport" content="width=800" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" href="css/style.css">
		<!-- Social Media Icon -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link href="assets/css/style.css" rel="stylesheet">
		<!-- Bootstrap -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
    	<link href="assets/css/style.css" rel="stylesheet">
		<!-- sweeat alert -->
	    <link href="assets/sweet_alert/dist/sweetalert.css" rel="stylesheet"/>
	    <link rel="stylesheet" href="assets/datatables/dataTables.bootstrap.css">

		<link rel="shortcut icon" type="image/jpg" href="favicon.ico"/>
	</head>
	<body>
		
<!-- - - - - - - 8<- - - - - - cut here - - - - - 8<- - - - - - - -->
		<script type="text/javascript" src="pano2vr_player.js"></script>
		<script type="text/javascript" src="skin.js"></script>
		<script src="webxr/three.min.js"></script>
		<script src="webxr/webxr-polyfill.min.js"></script>

		<div id="container"></div>

		<button type="button" class="btnhome" onclick="window.location.reload();">Home</button>
		<!-- Button trigger modal -->
		<button type="button" class="btnshare" data-toggle="modal" data-target="#btnShare">Share</button>
		<button type="button" class="btnkuis" data-toggle="modal" data-target="#btnKuis">Kuis</button>
		<!-- Modal -->
		<div class="modal fade" id="btnShare" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
				    <div class="modal-header">
				    	<h5 class="modal-title" id="exampleModalLongTitle">Visit & Share Us in our Social Media</h5>
				    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				    	<span aria-hidden="true">&times;</span>
				    </button>
					</div>
					<div class="modal-body">
				 		<p><a href="#" class="fa fa-facebook" target="_blank"></a>Pulau Kuy</p>
				 		<p><a href="https://www.instagram.com/pulaukuy/" class="fa fa-instagram" target="_blank"></a>@pulaukuy</p>
				 		<p><a href="https://mail.google.com/" class="fa fa-google" target="_blank"></a>pulaukuy@gmail.com</p>
					</div>
					<!-- <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary">Save changes</button>
				    </div> -->
				</div>
			</div>
		</div>
		<!-- Modal 2 -->
		<div class="modal fade" id="btnKuis" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLongTitle">Kuis</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<div class="home-box custom-box">
			      <div id="kontenku">
			      	<div class="row">
			      		<div class="col-sm-12">
                          <img src="assets/img/quiz.png" alt="assets/img/quiz.png" class="img img-responsive imgku">
              			</div>
              			<div class="col-sm-12 text-center">
	                		<p>Soal seputar pulau kelor, yang bertujuan untuk menambah wawasan</p>
	                		<p><a class="btn btn-primary btn-lg" onclick="mulai()" href="#" role="button">Klik disini</a></p>
		              	</div>
			          </div>
			        </div>
			      </div>
			    </div>
		      </div>
		      <!-- <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary">Save changes</button>
		      </div> -->
		    </div>
		  </div>
		</div>
		
		<script type="text/javascript">
			// membuat virtual tour dalam "container"
			pano=new pano2vrPlayer("container");
			// menambahkan skin pada virtual tour
			skin=new pano2vrSkin(pano);
			// muemuat konfigurasi virtual tour
			window.addEventListener("load", function() {
				pano.readConfigUrlAsync("pano.xml");
			});
		</script>
		<noscript>
			<p><b>Please enable Javascript!</b></p>
		</noscript>
<!-- - - - - - - 8<- - - - - - cut here - - - - - 8<- - - - - - - --> 
		<!-- Hack needed to hide the url bar on iOS 9, iPhone 5s --> 
		<div style="width:1px;height:1px;"></div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="assets/js/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="assets/js/bootstrap.min.js"></script>
		<!-- sweetalert -->
		<script src="assets/sweet_alert/dist/sweetalert.min.js"></script>
		<!-- datatable -->
		<script src="assets/datatables/jquery.dataTables.min.js"></script>
		<script src="assets/datatables/dataTables.bootstrap.min.js"></script>
		
		 
		 <script>
	      function mulai() {
	        $('#kontenku').load('ajax/soal.php');
	      }
	      function soaljawab() {
	        $('#kontenku').load('ajax/soaljawab.php');
	      }
	      function manage_user() {
	        $('#kontenku').load('ajax/user.php');
	      }
	    </script>
	</body>
</html>
