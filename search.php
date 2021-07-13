<?php  
session_start();
if(!isset($_SESSION['instauser'])) header("location: login.php"); 

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
    <h2><a href='./' class='no_u'>&larr;</a></h2>
</header>
<main>
    <p>        
        <input type="text" id="nama" placeholder="Masukkan username atau nama" size="100" autofocus=""> 
    </p>
    <p id="hasil">
        
    </p>
</main>
<script type="text/javascript">
    $('body').on('keyup', '#nama', function(){
        var nama = $(this).val();
        $("#hasil").html("");
        if(nama.length>0) {
            $.post("prosescari.php", {nama: nama})
            .done(function(data){
                $("#hasil").html(data);
            });
        }
    });
</script>
</body>
</html>