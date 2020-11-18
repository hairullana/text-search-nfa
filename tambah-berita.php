<?php
require 'connect-db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAMBAH ARTIKEL</title>

    <?php include "headtags.html"; ?>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand text-danger" href="index.php">Find Me</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-danger text-center">TAMBAH ARTIKEL</h3>
                        <hr class="mb-4">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="judul">Judul Artikel</label>
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukan Judul Berita" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="isi">Isi Artikel</label>
                                <textarea class="form-control" id="isi" name="isi" rows="8" placeholder="Masukan Isi Artikel" autocomplete="off"></textarea>
                            </div>
                            <a class="btn btn-md btn-secondary" href="index.php">Kembali</a>
                            <button type="submit" name="tambahBerita" id="tambahBerita" class="btn btn-md btn-danger float-right">Tambahkan Berita</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>


</body>

</html>

<?php

if (isset($_POST["tambahBerita"])) {
    $judul = htmlspecialchars($_POST["judul"]);
    $isi = htmlspecialchars($_POST["isi"]);

    $judul = mysqli_real_escape_string($connect, $judul);
    $isi = mysqli_real_escape_string($connect, $isi);


    $query = "INSERT INTO berita VALUES ('','$judul','$isi')";
    mysqli_query($connect, $query);

    if (mysqli_affected_rows($connect) > 0) {
        echo "
            <script>
                alert('berhasil menambahkan berita !');
            </script>
        ";
    } else {
        echo mysqli_error($connect);
        echo mysqli_affected_rows($connect);
    }
}

?>