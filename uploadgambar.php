<?php  
session_start();

if(!isset($_SESSION['instauser'])) die(json_encode($hasil));

include "db.config.php";

// simpan dulu ke tabel posting
$stmt = $mysqli->prepare("Insert Into posting (username, komen) Values (?,?)");
$stmt->bind_param('ss', $_SESSION['instauser'], $_POST['komen']);
$stmt->execute();
$idposting = $stmt->insert_id;
$stmt->close();

for($i = 0; $i < count($_FILES['files']['name']); $i++) {

    if($_FILES['files']['name'][$i]) {
        // insert dulu ke tabel gambar dan dapatkan idgambar
        $stmt = $mysqli->prepare("Insert Into gambar (idposting) Values (?)");
        $stmt->bind_param('i', $idposting);
        $stmt->execute();
        $idgambar = $stmt->insert_id;
        $stmt->close();

        //if no errors...
        if(!$_FILES['files']['error'][$i])  {
            // cari tau dulu extentionnya
            $ext = "";
            if($_FILES['files']['type'][$i] == 'image/jpeg' || $_FILES['files']['type'][$i] == 'image/jpg') {
                $ext = "jpg";
            } else if($_FILES['files']['type'][$i] == 'image/png') {
                $ext = "png";
            }
            move_uploaded_file($_FILES['files']['tmp_name'][$i], "gambar/".$idgambar.'.'.$ext);
            $message = 'Congratulations!  Your file was accepted.';

            // update lagi tabel gambar
            $stmt = $mysqli->prepare("Update gambar set extention=? Where idgambar=?");
            $stmt->bind_param('si', $ext, $idgambar);
            $stmt->execute();
            $stmt->close();

        } else {
            $message = 'Ooops!  Your upload triggered the following error: ' .$_FILES['files']['error'][$i];
        }
    } else { die('You did not select any file!'); }
}
echo $message;


$mysqli->close();
?>