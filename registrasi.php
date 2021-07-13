<?php 
session_start(); 
if(isset($_SESSION['instauser'])) header("location: index.php"); // kalo sudah login, ngapain registrasi new user
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instantgram</title>
    <meta content="Hendra Dinata" name="author"/>
</head>
<body>
    <form method="post" action="registrasi_proses.php" autocomplete="off">
        <fieldset>
            <legend>Registrasi</legend>
            <?php if(isset($_SESSION['instaerror'])) {
                echo "<p style='color: red;'>".$_SESSION['instaerror']."</p>";
                unset($_SESSION['instaerror']);
            } ?>
            <div><label>Username</label> <input type="text" name="username" maxlength="40" required autofocus></div>
            <div><label>Nama</label> <input type="text" name="nama" maxlength="45" required></div>
            <div><label>Password</label> <input type="password" name="password" required></div>
            <div><label>Ulangi Password</label> <input type="password" name="password2" required></div>
            <br>
            <div><button type="submit" name="registrasi">Registrasi</button></div>
        </fieldset>
    </form>
    <p><a href="login.php">&lt;&lt; Login</a></p>
</body>
</html>