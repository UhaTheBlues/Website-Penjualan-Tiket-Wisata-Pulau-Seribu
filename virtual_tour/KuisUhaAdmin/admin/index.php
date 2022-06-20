<?php 
session_start();
// if (!isset($_SESSION["login"])) {
//   header("Location: login.php");
//   exit;
// }
require "../koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Aplikasi Soal Acak - Admin</title>

    <!-- Bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
     <!-- sweeat alert -->
    <link href="../assets/sweet_alert/dist/sweetalert.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/datatables/dataTables.bootstrap.css">
  </head>
  <body>

      <div class="container-fluid">
      <div id="kontenku">
          <div class="row">
              <div class="col-sm-12">
                <img src="../assets/img/quiz.png" alt="../assets/img/quiz.png" class="img img-responsive imgku">
              </div>
              <div class="col-sm-12 text-center">
                <p>Tombol Dibawah untuk mengedit soal kuis</p>
                <p><a class="btn btn-primary btn-lg" onclick="soaljawab()" href="#" role="button">Klik disini</a></p>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../assets/js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- sweetalert -->
    <script src="../assets/sweet_alert/dist/sweetalert.min.js"></script>
    <!-- datatable -->
    <script src="../assets/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/datatables/dataTables.bootstrap.min.js"></script>

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
    <?php $query=mysqli_query($con,"DELETE FROM tbl_score WHERE id= '"."' "); ?>
  </body>
</html>