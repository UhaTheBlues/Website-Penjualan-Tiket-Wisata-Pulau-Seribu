<?php 
session_start();
require "../../koneksi.php";
// if (!isset($_SESSION["login"]) || $_SESSION['user'] != 'admin') {
//   header("Location: ".base_url."login.php");
//   exit;
// }

$username = $_POST['username'];
$password = $_POST['password'];
$password = password_hash($password,PASSWORD_DEFAULT);
$query = "INSERT INTO users VALUES ('','$username','$password')";
$result=mysqli_query($con, $query);
echo json_encode(array("status" => TRUE));

?>