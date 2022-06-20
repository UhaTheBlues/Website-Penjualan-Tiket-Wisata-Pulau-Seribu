<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }

$id_soal = $_POST['id_soal'];
$pilihan_jawab = $_POST['pilihan_jawab'];
$query = "INSERT INTO tbl_jawaban VALUES ('','$id_soal','$pilihan_jawab')";
$result=mysqli_query($con, $query);
echo json_encode(array("status" => TRUE));

?>