<?php  
session_start();
if(!isset($_SESSION['instauser'])) header("location: login.php"); 
if(!isset($_GET['id'])) header("location: index.php");
if(!is_numeric($_GET['id'])) header("location: index.php");

$myself = true;
$posting = array();

include "db.config.php"; 

//get user pemilik
$stmt = $mysqli->prepare("Select p.* From posting p Where p.idposting=?");
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$res = $stmt->get_result();
if($posting = $res->fetch_assoc()) {
    $myself = ($posting['username']==$_SESSION['instauser']);       
} else {
    // gambar tidak ditemukan
    $mysqli->close();
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Instantgram</title>
    <meta content="Hendra Dinata" name="author"/>
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <style type="text/css">
        .no_u:link, .no_u:visited { text-decoration: none !important; color: blue;}
    </style>
</head>
<body>
<header style="border-bottom: 1px solid grey;">
    <h2><a href='./<?php if(!$myself) echo "?user=".$posting['username']; ?>' class='no_u'>&larr;</a></h2>
</header>
<main style="padding: 5px 15px;">
    <div>
        <?php  
            //cari gambar
            $stmt = $mysqli->prepare("Select idgambar, extention From gambar Where idposting=? Order By idgambar");
            $stmt->bind_param('i', $posting['idposting']);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 1) {
                // tombol prev
                echo "<div style='float: left; width: 5%;'><h2><a href='#' class='no_u nav' tanda='<' title='Sebelumnya'>&laquo</a></h2></div>";
            }
            $row = $res->fetch_assoc(); // ambil gambar pertama saja
            echo "<div style='float: left; width: 90%; text-align: center;'>
                    <img src='gambar/".$row['idgambar'].".".$row['extention']."' style='max-width: 70%;' id='gambar' idgambar='".$row['idgambar']."' idposting='".$posting['idposting']."'>
            </div>";
            if($res->num_rows > 1) {
                // tombol next
                echo "<div style='float: right; text-align: right; width: 5%;'><h2><a href='#' class='no_u nav' tanda='>' title='Setelahnya'>&raquo</a></h2></div>";
            }
            $stmt->close();
        ?>
    </div>
    <div style="clear: both; padding: 15px 0px;">
        <div style='float: left; width: 85%;'><?php echo $posting['komen']; ?></div>
        <div style='float: right; width: 15%; text-align: right;' >
            <?php 
            //cek my jempol
            $stmt = $mysqli->prepare("Select idposting From jempol_like Where idposting=? And username=?");
            $stmt->bind_param('is', $posting['idposting'], $_SESSION['instauser']);
            $stmt->execute();
            $res = $stmt->get_result();
            $path = $res->num_rows ? "red-heart.png" : "black-heart.png";
            echo "<a idposting='".$posting['idposting']."' class='no_u jempol'>";
            echo "<img src='gambar/".$path."' id='jempol'>";
            echo "</a>";
            $stmt->close();

            // get total likes
            $stmt = $mysqli->prepare("Select idposting From jempol_like Where idposting=?");
            $stmt->bind_param('i', $posting['idposting']);
            $stmt->execute();
            $res = $stmt->get_result();
            echo "<span id='jumlah'>".$res->num_rows."</span> Likes "; // jumlah like ditaruh dalam kontainer <span>
            $stmt->close();
        ?>
        </div>
    </div>
    <div id="komen" style="clear: both; padding: 15px 0px;">
        <?php 
            // get balasan komens
            $stmt = $mysqli->prepare("Select u.nama, b.isi_komen From balasan_komen b Inner Join user u On b.username=u.username Where b.idposting=? Order By b.tanggal");
            $stmt->bind_param('i', $posting['idposting']);
            $stmt->execute();
            $res = $stmt->get_result();
            while($row=$res->fetch_assoc()) {
                echo $row['nama']." : ".$row['isi_komen']."<br>";
            }
            $stmt->close();
        ?>
    </div>
    <div style="margin-top: 10px;">
        <input type="text" id="balas" placeholder="Masukkan komen" maxlength="200" size="100"> <button type="button" id="submit">Submit</button>
    </div>
</main>
<script type="text/javascript">
    $('body').on('click', '.nav', function(){
        var idposting = $("#gambar").attr('idposting');
        var idgambar = $("#gambar").attr('idgambar');
        var tanda = $(this).attr('tanda');
        $.post("prevnextgambar.php", {idposting: idposting, idgambar: idgambar, tanda: tanda})
        .done(function(data){
            var objGambar = JSON.parse(data);
            if(objGambar.idgambar>0) {
                $("#gambar").attr('idgambar', objGambar.idgambar);
                $("#gambar").attr('src', 'gambar/'+objGambar.idgambar+'.'+objGambar.extention);
            }
        });
    });

    $('body').on('click', '#submit', function(){
        var komen = $("#balas").val();
        var idposting = $("#gambar").attr('idposting');
        $.post("balaskomen.php", {idposting: idposting, komen: komen})
        .done(function(data){
            $("#komen").append(data);
            $("#balas").val('');
        });
    });

    $('body').on('click', '.jempol', function(){
        var idposting = $(this).attr('idposting');
        $.post("likejempol.php", {idposting: idposting})
        .done(function(data){
            var objHasil = JSON.parse(data);
            $("#jempol").attr('src','gambar/'+objHasil.gambar);
            $("#jumlah").html(objHasil.jumlah);
        });
    });
</script>
</body>
</html>
<?php $mysqli->close(); ?>