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
        .gambar { max-width: 100px; max-height: 100px; }
    </style>
</head>
<body>
<header style="border-bottom: 1px solid grey;">
    <h2><a href='./' class='no_u'>&larr;</a></h2>
</header>
<main>
    <form id="frmData" method="post">
        <!-- jika satu object file utk upload banyak gambar sekaligus, tambahkan attribut multiple dan name nya berupa array -->
        <p><label>Pilih Gambar : </label> <input type="file" name="files[]" id="files" required accept=".jpg, .png" multiple></p>
        <output id="list"></output>
        <p><label>Masukkan Komen : </label> <textarea name="komen" id="komen"></textarea></p>
        <p><button type="button" id="upload">Upload</button></p>
    </form>
</main>
<script type="text/javascript">

    $('body').on('change', '#files', function(){
        $("#list").html('');
        for(var i=0; i < $(this).get(0).files.length; i++ ) {
            $("#list").append("Gambar " + (i+1) + " : <img src='" + URL.createObjectURL($(this).get(0).files[i]) + "' class='gambar'> " + $(this).get(0).files[i].name + "<br>");
        }
        $("#komen").focus();
    });

    $('body').on('click', '#upload', function(){
        var formData = new FormData($("#frmData")[0]);
        $.ajax({
            url: 'uploadgambar.php',
            type: 'POST',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function (response) {
              alert(response);
              window.location.replace('./');
            }
        });

    });
</script>
</body>
</html>