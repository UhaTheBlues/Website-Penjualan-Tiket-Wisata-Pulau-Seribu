<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }

$id = $_POST['id'];
$query2 = "DELETE FROM tbl_jawaban WHERE id = '$id' ";
$result2=mysqli_query($con, $query2);
echo json_encode(array("status" => TRUE));

?>