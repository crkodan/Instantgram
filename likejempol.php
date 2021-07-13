<?php  
session_start();
$hasil = array('gambar'=>'', 'jumlah'=>0);
if(!isset($_SESSION['instauser'])) die(json_encode($hasil));

include "db.config.php";
$idposting = $_POST['idposting'];

$stmt = $mysqli->prepare("Select idposting From jempol_like Where idposting=? And username=?");
$stmt->bind_param('is', $idposting, $_SESSION['instauser']);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows) {
    $sql = "Delete From jempol_like Where idposting=? And username=?";
    $hasil['gambar'] = "black-heart.png";
} else {
    $sql = "Insert Into jempol_like (idposting, username) Values (?,?)";
    $hasil['gambar'] =  "red-heart.png";
}
$stmt->close();

// tambah atau hapus my like
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('is', $idposting, $_SESSION['instauser']);
$stmt->execute();
$stmt->close();

// get total like
$stmt = $mysqli->prepare("Select idposting From jempol_like Where idposting=?");
$stmt->bind_param('i', $idposting);
$stmt->execute();
$res = $stmt->get_result();
$hasil['jumlah'] = $res->num_rows;
$stmt->close();

echo json_encode($hasil);

$mysqli->close();
?>