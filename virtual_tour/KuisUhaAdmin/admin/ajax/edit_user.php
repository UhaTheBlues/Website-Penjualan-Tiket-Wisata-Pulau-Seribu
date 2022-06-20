<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }
$id =$_GET['id'];
$query = "SELECT * FROM users WHERE id='$id'";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_assoc($result);
?>
<form id="form_edit_user">
  <div class="form-group">
    <input type="hidden" name="id" value="<?=$row['id'] ?>">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="<?=$row['username'] ?>" class="form-control" readonly>
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password" placeholder="password">
  </div>
</form>


