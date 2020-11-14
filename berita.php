<?php

// koneksi database
include 'connect-db.php';

$id = $_GET["id"];

// ambil data berita di database
$berita = mysqli_query($connect, "SELECT * FROM berita WHERE id_berita = $id");

$berita = mysqli_fetch_assoc($berita);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <title><?= $berita["judul"] ?></title>
</head>
<body>

    <div class="navbar navbar-dark bg-light fixed-top mb-5">
        <div class="container">
            <div class="navbar-brand"><a href="/text-search-nfa"><img src="logo.png" width=125 alt=""></a></div>
        </div>
    </div>

    <br>
    <br>
    
    <div class="container mt-5">
        <div class="card mt-5">
            <div class="card-header"><h3><?= $berita["judul"] ?></h3></div>
            <div class="card-body"><?= $berita["isi"] ?></div>
        </div>
    </div>
</body>
</html>