<?php
// Mengambil waktu awal proses
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;

// mengambil waktu selesai
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
// Store end time in a variable
$tend = $mtime;

$totaltime = ($tend - $tstart);


// koneksi database
include 'connect-db.php';


// jika tombol pencarian di tekan
if (isset($_POST["cari"])) {

    // ambil value range
    $range = $_POST["range"];
    // ambil isi form
    $keywordAsli = $_POST["keyword"];
    // biar aman
    $keyword = htmlspecialchars($keywordAsli);
    // buat semua huruf jadi kecil
    $keyword = strtolower($keyword);
    // explode
    $arrayKeyword = explode(' ', $keyword);
    // jumlah kata dalam keyword
    $jumlahArrayKeyword = count($arrayKeyword);
    // menyimpan total huruf pada keyword
    $totalHurufKeyword = 0;
    // pecah string
    for ($i = 0 ; $i < $jumlahArrayKeyword ; $i++){
        // pecah array jdi 2 dimensi
        $arrayKeyword[$i] = str_split($arrayKeyword[$i]);
        // hitung total huruf pada keyword
        $totalHurufKeyword += count($arrayKeyword[$i]);
    }

    // jumlah state, total semua huruf + 1 (initial state)
    $totalState = $totalHurufKeyword+1;

    // range
    if ($range == 1) {
        $beritaDB = mysqli_query($connect, "SELECT * FROM berita WHERE id_berita >= 1 AND id_berita <= 50");
    }else if($range == 2){
        $beritaDB = mysqli_query($connect, "SELECT * FROM berita WHERE id_berita >= 51 AND id_berita <= 100");
    }else if($range == 3){
        $beritaDB = mysqli_query($connect, "SELECT * FROM berita WHERE id_berita >= 101 AND id_berita <= 150");
    }else if($range == 4){
        $beritaDB = mysqli_query($connect, "SELECT * FROM berita WHERE id_berita >= 151 AND id_berita <= 200");
    }else if($range == 5){
        $beritaDB = mysqli_query($connect, "SELECT * FROM berita WHERE id_berita >= 201 AND id_berita <= 230");
    }else {
        $beritaDB = mysqli_query($connect, "SELECT * FROM berita");
    }

}else {
    // jika tidak ada keyword maka kembali ke halaman awal
    echo "
        <script>
            alert('Kembali Ke Halaman Awal');
            window.location.href = '/text-search-nfa';
        </script>
    ";
}

// mesin nfa
function transisi($berita){

    // mengambil variabel diluar fungsi
    global $arrayKeyword;
    global $totalState;
    global $jumlahArrayKeyword;

    // ubah kata jadi kecil
    $berita = strtolower($berita);
    // pecah string
    $arrayBerita = str_split($berita);
    // patokan kata keyword ke -n
    $k = 0;

    // nilai untuk return
    $hasil[0] = -1; // indeks nya, -1 artinya tidak terdapat pada indeks
    $hasil[1] = 0; // ditemukan dengan kata keyword yg mana

    // mulai mesin nya
    // perulangan sesuai dengan jumlah kata pada keyword
    for ($k=0 ; $k<$jumlahArrayKeyword; $k++) {
        
        // hitung total array
        $totalArrayBerita = count($arrayBerita);
        // patokan berita
        $n = 0;
        // patokan keyword
        $m = 0;

        // jika lebihbesar dri 0
        while ($totalArrayBerita > 0) {
            // apakah katanya sama
            if ($arrayBerita[$n] == $arrayKeyword[$k][$m]) {
                // jika kata ditemukan. eg : covid, indeks terakhir = m = 4, count(covid) = 4. if (4==4) : true
                if ($m == count($arrayKeyword[$k])-1) {
                    // tentukan indeks kata ditemukan. dikurangi jumlah karakter kata keyword untuk menemukan indeks awal ditemukan
                    $hasil[0] = $n - (count($arrayKeyword[$k]) - 1);
                    // return 2, menentukan kata yang mana
                    $hasil[1] = count($arrayKeyword[$k]);
                    // karena sdh ditemukan, $arrayBerita ubah jadi 0 supaya while berhenti
                    $totalArrayBerita = 0;
                    // ganti vari k supaya perulangan for juga berhenti
                    $k = $jumlahArrayKeyword;
                }
                
                // jika huruf sama maka increment n dan m
                $n++;
                $m++;
            } else {     // jika tidak sama
                // $m ulangi dari 0
                $m = 0;
                // $n increment untuk mencari yg sesuai dengan $m
                $n++;
            }
            
            // decrement $arrayBerita setiap while berjalan
            $totalArrayBerita--;
        }
    }

    // return array hasil
    return $hasil;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian kata "<?= $keyword ?>"</title>
    <?php include "headtags.html"; ?>
</head>
<body>
    <!-- ambil keyword -->
    <?php
    $keyword = $_POST["keyword"];
    ?>

    <!-- navbar -->
    <div class="navbar navbar-dark bg-light fixed-top mb-5">
        <div class="container">
            <div class="navbar-brand"><a href="/text-search-nfa" class="text-danger">Find Me</a></div>
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
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></div>
                                </div>
                                <!-- kolom pencarian -->
                                <input type="text" class="form-control" id="keyword" name="keyword" value="<?= $keyword ?>" autocomplete="off">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-database" aria-hidden="true"></i></div>
                                </div>
                                <!-- range database -->
                                <select name="range" id="range" class="form-control">
                                    <?php if ($range == 1) : ?>
                                        <option value=1 selected>1-50</option>
                                        <option value=2>51-100</option>
                                        <option value=3>101-150</option>
                                        <option value=4>151-200</option>
                                        <option value=5>201-230</option>
                                        <option value=6>All Articles</option>
                                    <?php elseif ($range == 2) : ?>
                                        <option value=1>1-50</option>
                                        <option value=2 selected>51-100</option>
                                        <option value=3>101-150</option>
                                        <option value=4>151-200</option>
                                        <option value=5>201-230</option>
                                        <option value=6>All Articles</option>
                                    <?php elseif ($range == 3) : ?>
                                        <option value=1>1-50</option>
                                        <option value=2>51-100</option>
                                        <option value=3 selected>101-150</option>
                                        <option value=4>151-200</option>
                                        <option value=5>201-230</option>
                                        <option value=6>All Articles</option>
                                    <?php elseif ($range == 4) : ?>
                                        <option value=1>1-50</option>
                                        <option value=2>51-100</option>
                                        <option value=3>101-150</option>
                                        <option value=4 selected>151-200</option>
                                        <option value=5>201-230</option>
                                        <option value=6>All Articles</option>
                                    <?php elseif ($range == 5) : ?>
                                        <option value=1>1-50</option>
                                        <option value=2>51-100</option>
                                        <option value=3>101-150</option>
                                        <option value=4>151-200</option>
                                        <option value=5 selected>201-230</option>
                                        <option value=6>All Articles</option>
                                    <?php elseif ($range == 6) : ?>
                                        <option value=1>1-50</option>
                                        <option value=2>51-100</option>
                                        <option value=3>101-150</option>
                                        <option value=4>151-200</option>
                                        <option value=5>201-230</option>
                                        <option value=6 selected>All Articles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <div class="col-md-8 offset-md-2">
                                    <div class="row">
                                        <!-- search -->
                                        <div class="col-md-6 mb-1">
                                            <button type="submit" id="cari" class="form-control btn btn-danger rounded-pill" name="cari">Search</button>
                                        </div>
                                        <!-- tambah berita -->
                                        <div class="col-md-6">
                                            <a class="form-control btn btn-danger rounded-pill text-light" href="tambah-berita.php">Add Article</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- informasi keyword -->
                    <div class="card-body ml-3">
                        <?php
                        printf("Waktu menampilkan halaman %f detik.", $totaltime);
                        ?><br>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger btn-block rounded-pill mt-3" data-toggle="modal" data-target="#exampleModal">
                            Quintuple NFA
                        </button>

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

                <!-- masukkan ke dalam mesin -->
                <?php $hasil = transisi($data["isi"]); ?>
                <!-- jika terdapat kata yg ditemukan, maka tampilan -->
                <?php if ($hasil[0] > -1) : ?>
                    <!-- kotak berita -->
                    <div class="col-md-6">
                        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                            <div class="col p-4 flex-column position-static">

                                <!-- judul -->
                                <h3 class="mb-1"><?= substr($data["judul"], 0, 40) . "..." ?></h3>

                                <!-- informasi ditemukannya pada indeks ke berapa -->
                                <small class="mb-1 text-muted">Ditemukan pada indeks ke-<?= $hasil[0]; ?></small>

                                <!-- isi artikel -->
                                <p class="card-text mb-1">
                                    <?php
                                    // jika ditemukan pada 150 kata pertama
                                    if ($hasil[0] < 150) {
                                        // tampilan preview berita
                                        $isiBerita = substr($data["isi"], 0, 175) . "...";
                                        // pecah
                                        $isiBerita = str_split($isiBerita);
                                        // hitung array
                                        $totalIsiBerita = count($isiBerita);
                                        // untuk indeks 
                                        $i = 0;
                                        // mencari keyword
                                        while ($totalIsiBerita > 0) {
                                            // kalau keyword maka buat tebal
                                            if ($i >= $hasil[0] && $i <= $hasil[0] + $hasil[1] - 1) {
                                                echo "<b>" . $isiBerita[$i] . "</b>";
                                            } else {
                                                // jika tidak ya biarin gan
                                                echo $isiBerita[$i];
                                            }
                                            // increment index
                                            $i++;
                                            // decrement
                                            $totalIsiBerita--;
                                        }
                                    } else {            // jika keyword ditemukan di atas 150 kata
                                        // tampilan preview berita dari indeks ditemukan - 20
                                        $isiBerita = "..." . substr($data["isi"], $hasil[0] - 20, 175) . "...";
                                        // pecah
                                        $isiBerita = str_split($isiBerita);
                                        // hitung array
                                        $totalIsiBerita = count($isiBerita);
                                        // untuk indeks
                                        $i = 0;
                                        // mencari keyword
                                        while ($totalIsiBerita > 0) {
                                            // buat tebal keywordnya
                                            if ($i >= 23 && $i <= 23 + $hasil[1] -1) {
                                                echo "<b>" . $isiBerita[$i] . "</b>";
                                            } else {
                                                echo $isiBerita[$i];
                                            }
                                            $i++;
                                            $totalIsiBerita--;
                                        }
                                    }
                                    ?>
                                </p>
                                <!-- read more -->
                                <a href="berita.php?id=<?= $data['id_berita']; ?>" target="_blank" class="btn btn-danger">read more</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Quintuple NFA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- kata kunci -->
                    Kata Kunci = <?= $keyword ?> <br>
                    <!-- total state -->
                    Total State = <?= $totalState ?> <br>
                    <!--  initial state -->
                    Initial State = 1 <br>
                    <!-- final state -->
                    Final State = 
                    <?php
                        // menampung jumlah kata sebelumnya
                        $x=0;
                        for($i=0;$i<count($arrayKeyword);$i++){
                            // kalau kata pertama tdk perlu pakai x. ditambah 1 karena dipakai initial state 1 biji
                            if ($i==0){
                                echo count($arrayKeyword[$i])+1 . " ";
                            }else{ // perlu ditambahkan x karena ada kata di sebelumnya
                                echo count($arrayKeyword[$i])+$x+1 . " ";
                            }
                            // operasi nambah x
                            $x += count($arrayKeyword[$i]);
                        }
                    ?><br>
                    <!-- tabel informasi delta -->
                    <table class="table text-center mt-2">
                        <tr>
                            <th>State Awal</th>
                            <th>Input</th>
                            <th>State Tujuan</th>
                        </tr>
                        <!-- inisialisasikan x untuk menghitung jumlah state sebelumnya jika berada pada kata ke 2,3,dst  -->
                        <?php $x=0;$j=0; while ($j < count($arrayKeyword) ) : ?>
                            <?php
                                // jika bukan kata pertama, tambahkan var x dengan jumlah kata sblmnya
                                if ($j > 0) {
                                    $x+=count($arrayKeyword[$j-1]);
                                }
                            ?>
                            <!-- perulangan array per kata -->
                            <?php for ($i = 0; $i < count($arrayKeyword[$j]); $i++) : ?>
                                <tr>
                                    <!-- jika kata pertama -->
                                    <?php if ($j == 0) : ?>
                                        <td><?= $i + 1; ?></td>
                                        <td><?= $arrayKeyword[$j][$i]; ?></td>
                                        <td><?= $i + 2; ?></td>
                                    <!-- jika bkn kata pertama -->
                                    <?php elseif ($j > 0) : ?>
                                        <!-- jika huruf pertama pada suatu kata di keyword (dari state 1 lagi) -->
                                        <?php if ($i == 0) : ?>
                                            <td><?= $i+1; ?></td>
                                        <!-- jika tidak -->
                                        <?php else : ?>
                                            <td><?= $x+$i+1; ?></td>
                                        <?php endif; ?>
                                        <td><?= $arrayKeyword[$j][$i]; ?></td>
                                        <td><?= $x+$i+2; ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endfor; ?>
                            <?php $j++; ?>
                        <?php endwhile; ?>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>