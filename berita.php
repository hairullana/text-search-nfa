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
    <?php include "headtags.html"; ?>
    <title><?= $berita["judul"] ?></title>
</head>

<body>

    <div class="navbar navbar-dark bg-light fixed-top mb-5">
        <div class="container">
            <div class="navbar-brand"><a href="/text-search-nfa" class="text-danger">Find Me</a></div>
        </div>
    </div>

    <br>
    <br>

    <div class="container mt-5 mb-5">
        <div class="card mt-5">
            <div class="card-header">
                <h3><?= $berita["judul"] ?></h3>
            </div>
            <div class="container">
                <div class="card-body text-justify"><?= $berita["isi"] ?></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>