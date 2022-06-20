<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }
$id =$_GET['id'];
$query = "SELECT * FROM tbl_soal WHERE id='$id'";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_assoc($result);
?>
<form id="form_edit_soal">
  <div class="form-group">
    <input type="hidden" name="id" value="<?=$row['id'] ?>">
    <label for="soal">Soal</label>
    <textarea name="soal" id="soal" cols="30" rows="10" class="form-control"><?=$row['soal'] ?></textarea>
  </div>
  <div class="form-group">
    <label for="jawaban">Jawaban</label>
    <input type="text" class="form-control" id="jawaban" name="jawaban" value="<?=$row['jawaban'] ?>" placeholder="jawaban">
  </div>
</form>


