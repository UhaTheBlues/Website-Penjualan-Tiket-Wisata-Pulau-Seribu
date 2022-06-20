<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }

$id =$_GET['id'];
$query = "SELECT * FROM tbl_jawaban WHERE id='$id'";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_assoc($result);
?>
<form id="form_edit_jawaban">
  <div class="form-group">
    <input type="hidden" name="id" value="<?=$row['id'] ?>">
  </div>
  <div class="form-group">
    <label for="jawaban">Jawaban</label>
    <input type="text" class="form-control" id="pilihan_jawab" name="pilihan_jawab" value="<?=$row['pilihan_jawab'] ?>" placeholder="jawaban">
  </div>
</form>


