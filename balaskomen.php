<?php 
session_start();

$idposting = $_POST['idposting'];
$komen = $_POST['komen'];

if(!isset($_SESSION['instauser'])) die(json_encode($hasil));
include "db.config.php";
$stmt = $mysqli->prepare("Insert Into balasan_komen (idposting, username, isi_komen) Values (?,?,?)");
$stmt->bind_param('iss', $idposting, $_SESSION['instauser'], $komen);
$stmt->execute();
if($stmt->affected_rows == 1) {
    echo $_SESSION['instanama']." : ".$komen."<br>";
}
$mysqli->close();
?>