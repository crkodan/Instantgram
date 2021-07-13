<?php  
session_start();
if(isset($_SESSION['instauser'])) header("location: index.php"); 

if(isset($_POST['login'])) {
    include "db.config.php";
    $stmt = $mysqli->prepare("Select salt From user Where username=?");
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row = $res->fetch_assoc()) {
        $password = md5($row['salt'].md5($_POST['password']).$row['salt']);
        $stmt->close();

        $stmt = $mysqli->prepare("Select username, nama From user Where username=? AND password=?");
        $stmt->bind_param('ss', $_POST['username'], $password);
        $stmt->execute();
        $res = $stmt->get_result();
        if($row = $res->fetch_assoc()) {
            $_SESSION['instauser'] = $row['username'];
            $_SESSION['instanama'] = $row['nama'];
            header("location: index.php");
            $stmt->close();
        } else {
            $_SESSION['instaerror'] = "Username/ Password salah";
            header("location: login.php");
        }
    } else {
        $_SESSION['instaerror'] = "Username/ Password salah"; // username tidak ada di db
        header("location: login.php");
    }

    $mysqli->close();
}
?>