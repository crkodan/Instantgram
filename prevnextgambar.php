<?php  
session_start();

$idposting = $_POST['idposting'];
$idgambar = $_POST['idgambar'];
$tanda = $_POST['tanda'];
$hasil=array('idgambar'=>0, 'extention'=>'');

if(!isset($_SESSION['instauser'])) die(json_encode($hasil));

include "db.config.php";
$stmt = $mysqli->prepare("Select idgambar, extention From gambar Where idposting=? AND idgambar".$tanda."? LIMIT 1");
$stmt->bind_param('ii', $idposting, $idgambar);
$stmt->execute();
$res = $stmt->get_result();
if($row = $res->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode($hasil);
}
$mysqli->close();
?>