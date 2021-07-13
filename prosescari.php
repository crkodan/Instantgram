<?php 
session_start();
if(!isset($_SESSION['instauser']) || !isset($_POST['nama'])) die('');
if(empty($_POST['nama'])) die('');
include "db.config.php";
$hasil = "";
$cari = '%'.$_POST['nama'].'%';
$stmt = $mysqli->prepare("Select username, nama From user Where username <> ? AND (username LIKE ? OR nama LIKE ?)");
$stmt->bind_param('sss', $_SESSION['instauser'], $cari, $cari);
$stmt->execute();
$res = $stmt->get_result();
while($row=$res->fetch_assoc()) {
    $hasil .= "<a href='./?user=".$row['username']."'>".$row['nama']."</a><br>";
}
echo $hasil;
$mysqli->close();
 ?>