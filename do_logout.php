<?php  
session_start();
if(isset($_SESSION['instauser'])) {
    unset($_SESSION['instauser']);
}
session_destroy();
header("location: login.php");
?>