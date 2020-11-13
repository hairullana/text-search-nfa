<?php

include 'connect-db.php';

$berita = mysqli_query($connect, "SELECT * FROM berita");


if (isset($_POST["cari"])){
    // ambil isi form
    $keyword = $_POST["keyword"];
    // biar aman
    $keyword = htmlspecialchars($keyword);
    $keyword = strtolower($keyword);
    // pecah string
    $string = str_split($keyword);
    // jumlah state
    $totalState = count($string) + 1    ;

    
}


function transisi ($kata) {
    global $string;
    global $totalState;

    $lokasi = 0;

    $kata = strtolower($kata);
    $kata = str_split($kata);
    $totalKata = count($kata);

    $kondisi = false;
    $indeks = -1;
    $n = 0;
    $m = 0;
    while ($totalKata > 0) {
        // apakah katanya sama
        if ($kata[$n] == $string[$m]) {
            if ($m == $totalState-2) {
                $kondisi = true;
                $indeks = $n - ($totalState-2);
                $totalKata = 0;

            }
            $n++;
            $m++;
        }else {
            $m = 0;
            $n++;
            $kondisi = false;
        }

        $totalKata--;   
    }

    return $indeks;
    //$indeks = 
    // if ( $kondisi == true ) {
    //     echo "ditemukan pada indeks ke-" . $indeks ;
    // }else {
    //     echo "tidak ditemukan";
    // }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h3>NFA</h3>

    


    <form action="" method="POST">
        <input type="text" id="keyword" name="keyword" placeholder="keyword">
        <button type="submit" id="cari" name="cari">Cari Berita</button>
    </form>

    <br>
    <br>


    <?php if (isset($_POST["cari"])) : ?>
        Kata Kunci = <?= $keyword ?> <br>
        Total State = <?= $totalState ?> <br>
        Nama State = <?php for ($n=1;$n<=$totalState;$n++){ echo $n . " "; } ?> <br>
        Initial State = 1 <br>
        Final State = <?= $totalState ?><br>
        Delta = <br>

        

        <table cellpadding=15 border=1>
            <tr>
                <td>ID</td>
                <td>Judul</td>
                <td>Isi</td>
            </tr>
            <tr>
            <?php foreach ($berita as $data) : ?>
                <?php if ( transisi($data["isi"]) > -1 ) : ?>
                <td><?= $data["id_berita"] ?></td>
                <td><?= $data["judul"] ?></td>
                <td><?= $data["isi"] ?></td>
                <td><?php echo "ditemukan pada indeks ke-" .  transisi($data["isi"]); ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- <br><br>
        <b>Nyoba aja</b><br>
        beruang = <?php transisi("beruang"); ?><br>
        marisa = <?php transisi("marisa"); ?><br>
        firdancok = <?php transisi("firdancok"); ?><br>
        punten = <?php transisi("punten"); ?><br>
        febri bangsat = <?php transisi("febri bangsat"); ?><br>
        hairul bangsa = <?php transisi("hairul bangsa"); ?><br> -->
    <?php else : ?>
    
        <table cellpadding=15 border=1>
            <tr>
                <td>ID</td>
                <td>Judul</td>
                <td>Isi</td>
            </tr>
            <tr>
            <?php foreach ($berita as $data) : ?>
                <td><?= $data["id_berita"] ?></td>
                <td><?= $data["judul"] ?></td>
                <td><?= $data["isi"] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>
</body>
</html>