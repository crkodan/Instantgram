<?php  
session_start();
if(!isset($_SESSION['instauser'])) header("location: login.php"); 

function cetakheader($nama, $myself=true) {
    if(!$myself) {
        echo "<div style='float: left; margin-right: 15px;'>
            <h2><a href='search.php' class='no_u'>&larr;</a></h2>
        </div>";
    }
    echo "<div style='float: left;'><h2>".$nama."</h2></div>";
    if($myself) {
        echo "<div style='float: right;'><a href='do_logout.php'>Logout</a></div>";
        echo "<div style='float: right; margin-right: 20px;'><a href='search.php'>Cari User</a></div>";
    }
}

include "db.config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instantgram</title>
    <meta content="Hendra Dinata" name="author"/>
    <style type="text/css">
        .no_u:link, .no_u:visited { text-decoration: none !important; color: blue;}
        .gambar { max-width: 100%; max-height:100%; }
    </style>
</head>
<body>
<header>
    <?php 
    $myself=true;
    $username = $_SESSION['instauser'];
    $nama = $_SESSION['instanama'];
    if (isset($_GET['user'])) {
        if ($_GET['user'] != $username) {
            $myself = false;
            $username = $_GET['user'];

            // get nama
            $stmt = $mysqli->prepare("Select nama From user Where username=?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $res = $stmt->get_result();
            if($row = $res->fetch_assoc()) {
                $nama = $row['nama'];
            }
            $stmt->close();
        }
    }
    cetakheader($nama, $myself); 
    ?>
</header>
<main style="clear: both;">
    <div style="margin: 10px auto; width: 800px;">
<?php  
    $stmt = $mysqli->prepare("Select idposting, komen From posting Where username=? Order By tanggal desc");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    while($row=$res->fetch_assoc()) {
        $stmt2 = $mysqli->prepare("Select idgambar, extention from gambar Where idposting=? Limit 1");
        $stmt2->bind_param('i', $row['idposting']);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $row2 = $res2->fetch_assoc();
        echo "<a href='detil.php?id=".$row['idposting']."'>
            <div style='float: left; width:260px; height:260px; '>
                <img src='gambar/".$row2['idgambar'].".".$row2['extention']."' title='".$row['komen']."' class='gambar'>
            </div>
        </a>";
        $stmt2->close();
    }
    $stmt->close();
?>    
    </div>
    <div style='clear: both;'>
        <?php if($myself) {
            echo "<a href='tambahposting.php'>Tambah Posting</a>";
        } ?>
    </div>
</main>
</body>
</html>
<?php 
    $mysqli->close();
?>