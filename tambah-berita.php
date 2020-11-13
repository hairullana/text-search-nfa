<?php

require 'connect-db.php';



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <label for="judul">Judul Berita :</label><br>
        <input type="text" id="judul" name="judul"><br>
        <label for="isi">Isi Berita :</label><br>
        <textarea name="isi" id="isi" cols="30" rows="10"></textarea><br>
        <button type="submit" name="tambahBerita" id="tambahBerita">Tambahkan Berita</button>
    </form>
</body>
</html>

<?php

if (isset($_POST["tambahBerita"])) {
    $judul = htmlspecialchars($_POST["judul"]);
    $isi = htmlspecialchars($_POST["isi"]);

    $judul = mysqli_real_escape_string($connect,$judul);
    $isi = mysqli_real_escape_string($connect,$isi);


    $query = "INSERT INTO berita VALUES ('','$judul','$isi')";
    mysqli_query($connect,$query);

    if (mysqli_affected_rows($connect) > 0){
        echo "
            <script>
                alert('berhasil menambahkan berita !');
            </script>
        ";
    }else {
        echo mysqli_error($connect);
        echo mysqli_affected_rows($connect);
    }
    
}

?>