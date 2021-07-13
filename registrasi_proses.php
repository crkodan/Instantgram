<?php  
session_start();
if(isset($_POST['registrasi'])) {
    include "db.config.php";
    $error="";
    $stmt = $mysqli->prepare("Select username From user Where username=?");
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows>0) {
        $error .= "Username sudah ada<br>";
    }
    if($_POST['password']!=$_POST['password2']) {
       $error .= "Password tidak sama";
    }
    $stmt->close();

    if(!empty($error)) {
        $_SESSION['instaerror'] = $error;
        $mysqli->close();
        header("location: registrasi.php");
    } else {
        $salt = md5(date('YmdHis'));
        $password = md5($salt.md5($_POST['password']).$salt);
        $stmt = $mysqli->prepare("Insert Into user Values (?,?,?,?)");
        $stmt->bind_param('ssss', $_POST['username'], $_POST['nama'], $password, $salt);
        $stmt->execute();
        header("location: login.php?reg=1");
        $mysqli->close();
    }
} else {
    header("location: index.php");
}

?>