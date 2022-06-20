<?php 
session_start();
require "../koneksi.php";
// if (!isset($_SESSION["login"])) {
//   header("Location: ".base_url."login.php");
//   exit;
// }
$id = $_POST['id'];
$jawaban = $_POST['jawaban'];
$id_user = $_POST['id'];
$result=mysqli_query($con, "SELECT jawaban FROM tbl_soal WHERE id = '$id'");
$hasil = mysqli_fetch_assoc($result);

if ($hasil['jawaban']==$jawaban) {
	$data = 'jawaban anda benar, anda dapat 4 point';
	$nilai = 4;

	$cari = mysqli_query($con,"SELECT score FROM tbl_score  ");
	if (mysqli_num_rows($cari) > 0) {
		$score = mysqli_fetch_assoc($cari);
		mysqli_query($con,"UPDATE tbl_score SET score = '".$score['score']."' + $nilai  ");

	}else{
		mysqli_query($con,"INSERT INTO tbl_score VALUES ('$nilai')");
	}
}else{
	$data = 'jawaban anda salah, anda dikurangi 1 point';
	$nilai = 1;
	$cari = mysqli_query($con,"SELECT score FROM tbl_score  ");
	if (mysqli_num_rows($cari) > 0) {
		$score = mysqli_fetch_assoc($cari);
		mysqli_query($con,"UPDATE tbl_score SET score = '".$score['score']."' - '$nilai'  ");

	}else{
		$nilai = -1;
		mysqli_query($con,"INSERT INTO tbl_score VALUES ('$nilai')");
	}
}

$nilaisekarang = mysqli_fetch_assoc(mysqli_query($con,"SELECT score FROM tbl_score "));
echo $data.'/'.$nilaisekarang['score'];
?>