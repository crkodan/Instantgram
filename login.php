<?php 
session_start(); 
if(isset($_SESSION['instauser'])) header("location: index.php"); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instantgram</title>
    <meta content="Hendra Dinata" name="author"/>
</head>
<body>
    <form method="post" action="do_login.php" autocomplete="off">
        <fieldset>
            <legend>Login</legend>
            <?php 
            if(isset($_GET['reg'])) {
                if($_GET['reg']==1) {
                    echo "<p style='color: green;'>Registrasi Berhasil</p>";
                }
            } 
            if(isset($_SESSION['instaerror'])) {
                echo "<p style='color: red;'>".$_SESSION['instaerror']."</p>";
                unset($_SESSION['instaerror']);
            }
            ?>
            <div><label>Username</label> <input type="text" name="username" autofocus required></div>
            <div><label>Password</label> <input type="password" name="password" required></div>
            <br>
            <div><button type="submit" name="login">Login</button></div>
            <br>
            <a href="registrasi.php">Registrasi User Baru</a>
        </fieldset>
    </form>
</body>
</html>