<?php

// koneksi database
include 'connect-db.php';

// ambil data berita di database
$beritaDB = mysqli_query($connect, "SELECT * FROM berita");

// jika tombol pencarian di tekan
if (isset($_POST["cari"])){

    // keyword asli
    $keywordAsli = $_POST["keyword"];

    // ambil isi form dan tambahkan spasi sebelumnya
    $keyword = " " . $_POST["keyword"];

    // biar aman
    $keyword = htmlspecialchars($keyword);

    // buat semua huruf jadi kecil
    $keyword = strtolower($keyword);

    // pecah string
    $arrayKeyword = str_split($keyword);

    // jumlah state
    $totalState = count($arrayKeyword) + 1;
}

// mesin nfa
function transisi ($berita) {

    // mengambil variabel diluar fungsi
    global $arrayKeyword;
    global $totalState;

    // ubah kata jadi kecil
    $berita = strtolower($berita);
    // pecah string
    $arrayBerita = str_split($berita);
    // hitung total array
    $totalArrayBerita = count($arrayBerita);

    
    // indeks ditemukannya keyword, index -1 artinya keyword tidak ditemukan pada indeks 0,1,2,dst
    $indeks = -1;
    // patokan berita
    $n = 0;
    // patokan keyword
    $m = 0;

    // true jika $arrayBerita > 0
    while ($totalArrayBerita > 0) {
        if ($n == 0){
            // spasi jangan dimasukkan
            $m = 1;
            // apakah katanya sama
            if ($arrayBerita[$n] == $arrayKeyword[$m]) {
                // jika kata ditemukan. eg : covid, indeks terakhir = m = 4, $totalstate - 2 = (total huruf covid + 1) - 2 = 6-2 = 4. if (4==4) a.k.a true
                if ($m == $totalState-2) {
                    // tentukan indeks kata ditemukan. eg : covid, tercovid, masuk ke kondisi ini ketika $n=7. $indeks = $n - ($totalState-2) = 7 - (6-2) = 7-4 = 3 (indeks ke 3 = c). $totalState-2 karena mencari total indeks covid = 4.
                    $indeks = $n - ($totalState-2);
                    // karena sdh ditemukan, $arrayBerita ubah jadi 0 supaya while berhenti
                    $totalArrayBerita = 0;
                }
    
                // jika huruf sama maka increment n dan m
                $n++;
                $m++;
            }else {     // jika tidak sama
                // $m ulangi dari 0
                $m = 0;
                // $n increment untuk mencari yg sesuai dengan $m
                $n++;
            }
        }else {
            // apakah katanya sama
            if ($arrayBerita[$n] == $arrayKeyword[$m]) {
                
                // jika kata ditemukan. eg : covid, indeks terakhir = m = 4, $totalstate - 2 = (total huruf covid + 1) - 2 = 6-2 = 4. if (4==4) a.k.a true
                if ($m == $totalState-2) {
                    // tentukan indeks kata ditemukan. eg : covid, tercovid, masuk ke kondisi ini ketika $n=7. $indeks = $n - ($totalState-2) = 7 - (6-2) = 7-4 = 3 (indeks ke 3 = c). $totalState-2 karena mencari total indeks covid = 4.
                    $indeks = $n - ($totalState-2);
                    // karena sdh ditemukan, $arrayBerita ubah jadi 0 supaya while berhenti
                    $totalArrayBerita = 0;
                }
    
                // jika huruf sama maka increment n dan m
                $n++;
                $m++;
            }else {     // jika tidak sama
                // $m ulangi dari 0
                $m = 0;
                // $n increment untuk mencari yg sesuai dengan $m
                $n++;
            }
        }

        // decrement $arrayBerita setiap while berjalan
        $totalArrayBerita--;   
    }

    // return indeks 
    return $indeks;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Search NFA</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <!-- My CSS -->
    <style>
        .form-control, {
		font-size: 16px;
		transition: all 0.4s;
		box-shadow: none;
        }
        .form-control:focus, {
            border-color: #5cb85c;
        }
        .form-control, .btn, .input-group-text {
            border-radius: 50px;
            outline: none !important;
        }
        .input-group-text {
            background:white;
        }
    </style>

</head>
<body>

    


    <!-- jika pencarian dilakukan -->
    <?php if (isset($_POST["cari"])) : ?>

        <!-- navbar -->
        <div class="navbar navbar-dark bg-light fixed-top mb-5">
            <div class="container">
                <div class="navbar-brand"><a href="/text-search-nfa"><img src="logo.png" width=125 alt=""></a></div>
            </div>
        </div>
        <!-- end navbar -->
        

        <!-- card untuk informasi pencarian -->
        <div class="container my-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card mt-5">
                        <div class="card-header text-center mb-0">
                            <!-- form pencarian -->
                            <form action="" method="POST">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                                    </div>
                                    <input type="text" class="form-control" id="keyword" name="keyword" value="<?= $keyword ?>">
                                </div>
                                <div class="form-group mt-2">
                                    <div class="col-md-4 offset-md-4">
                                        <button type="submit" id="cari" class="form-control btn btn-danger" name="cari">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- informasi keyword -->
                        <div class="card-body ml-3">
                            Kata Kunci = <?= $keywordAsli ?> <br>
                            Total State = <?= $totalState ?> <br>
                            Nama State = <?php for ($n=1;$n<=$totalState;$n++){ echo $n . " "; } ?> <br>
                            Initial State = 1 <br>
                            Final State = <?= $totalState ?><br>
                            Delta = <br>
                            <table class="table text-center">
                                <tr>
                                    <th>State Awal</th>
                                    <th>Input</th>
                                    <th>State Tujuan</th>
                                </tr>
                                <?php for($i=0;$i<strlen($keyword);$i++) : ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= $arrayKeyword[$i] ?></td>
                                    <td><?= $i+2 ?></td>
                                </tr>
                                <?php endfor; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- list berita yg ditemukan -->
        <div class="container">
            <div class="row">

            <!-- mengambil data berita -->
            <?php foreach ($beritaDB as $data) : ?>
                
                <!-- jika terdapat kata yg ditemukan -->
                <?php if ( transisi($data["isi"]) >= 0 ) : ?>

                    <!-- kotak berita -->
                    <div class="col-md-6">
                        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-4 flex-column position-static">

                                <!-- judul -->
                                <h3 class="mb-1"><?= substr($data["judul"],0,30) . "..." ?></h3>

                                <!-- informasi indeks -->
                                <div class="mb-1 text-muted">ditemukan pada indeks ke-<?= transisi($data["isi"]); ?></div>

                                <!-- isi artikel -->
                                <p class="card-text mb-1">
                                    <?php
                                        // jika ditemukan pada 150 kata pertama
                                        if ( transisi($data["isi"]) < 150 ) {
                                            // tampilan preview berita
                                            $isiBerita = substr($data["isi"],0,175) . "...";
                                            // pecah
                                            $isiBerita = str_split($isiBerita);
                                            // hitung array
                                            $totalIsiBerita = count($isiBerita);
                                            // untuk indeks 
                                            $i=0;
                                            // mencari keyword
                                            while( $totalIsiBerita > 0 ){
                                                // kalau keyword maka buat tebal
                                                if ( $i >= transisi($data["isi"]) && $i <= transisi($data["isi"])+($totalState-2)) {
                                                    echo "<b>" . $isiBerita[$i] . "</b>";
                                                }else {
                                                    // jika tidak ya biarin gan
                                                    echo $isiBerita[$i];
                                                }
                                                // increment index
                                                $i++;
                                                // decrement
                                                $totalIsiBerita--;
                                            }
                                        }else {            // jika keyword ditemukan di atas 150 kata
                                            // tampilan preview berita dari indeks ditemukan - 20
                                            $isiBerita = "..." . substr($data["isi"],transisi($data['isi'])-20,175) . "...";
                                            // pecah
                                            $isiBerita = str_split($isiBerita);
                                            // hitung array
                                            $totalIsiBerita = count($isiBerita);
                                            // untuk indeks
                                            $i=0;
                                            // mencari keyword
                                            while( $totalIsiBerita > 0 ){
                                                // buat tebal keywordnya
                                                if ( $i >= 23 && $i <= 23+($totalState-2)) {
                                                    echo "<b>" . $isiBerita[$i] . "</b>";
                                                }else {
                                                    echo $isiBerita[$i];
                                                }
                                                $i++;
                                                $totalIsiBerita--;
                                            }
                                        }
                                    ?>
                                </p>
                                <!-- read more -->
                                <a href="berita.php?id=<?= $data['id_berita']; ?>" class="btn btn-danger">read more</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>

        <!-- <br><br>
        <b>Nyoba aja</b><br>
        beruang = <?php transisi("beruang"); ?><br>
        marisa = <?php transisi("marisa"); ?><br>
        firdancok = <?php transisi("firdancok"); ?><br>
        punten = <?php transisi("punten"); ?><br>
        febri bangsat = <?php transisi("febri bangsat"); ?><br>
        hairul bangsa = <?php transisi("hairul bangsa"); ?><br> -->
    

    <?php else : ?>

        <div class="jumbotron text-center mt-5 mb-0" style="background:white">
            <img src="logo.png" width=50% alt="">
        </div>



        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form action="" method="POST">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                            </div>
                            <input type="text" class="form-control" id="keyword" name="keyword">
                        </div>
                        <div class="form-group mt-3">
                            <div class="col-md-4 offset-md-4">
                                <button type="submit" id="cari" class="form-control btn btn-danger" name="cari">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="bootstrap-4.4.1/js/bootstrap.min.js"></script>

</body>
</html>