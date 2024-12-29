<?php
require_once '../Config.php';

// Ambil data vektor S dari database wisata
$sql_wisata = "SELECT id_wisata, nama_wisata FROM wisata";
$result_wisata = mysqli_query($conn, $sql_wisata);

if (!$result_wisata) {
    die("Query gagal: " . mysqli_error($conn));
}

// Hitung nilai vektor S seperti sebelumnya
$sql_kriteria = "SELECT * FROM kriteria";
$result_kriteria = mysqli_query($conn, $sql_kriteria);
$normalized_weights = [];
while ($row = mysqli_fetch_assoc($result_kriteria)) {
    $bobot = $row['bobot'] / 100;
    $normalized_weights['C' . $row['id_kriteria']] = $row['keterangan'] === 'Cost' ? -$bobot : $bobot;
}

$sql_kriteria_nilai = "SELECT * FROM kriteria_nilai";
$result_kriteria_nilai = mysqli_query($conn, $sql_kriteria_nilai);
$kriteria_nilai_map = [];
while ($row = mysqli_fetch_assoc($result_kriteria_nilai)) {
    $kriteria_nilai_map[$row['id_kriteria']][$row['kinerja']] = $row['nilai_kriteria'];
}

// Hitung vektor S untuk semua alternatif
$vector_data = [];
$total_s = 0;

mysqli_data_seek($result_wisata, 0);
while ($row = mysqli_fetch_assoc($result_wisata)) {
    // Ambil nilai kriteria dari database
    $sql_detail = "SELECT * FROM wisata WHERE id_wisata = " . $row['id_wisata'];
    $result_detail = mysqli_query($conn, $sql_detail);
    $detail = mysqli_fetch_assoc($result_detail);

    $c1 = isset($kriteria_nilai_map[1][$detail['lokasi']]) ? $kriteria_nilai_map[1][$detail['lokasi']] : 0;
    $c2 = isset($kriteria_nilai_map[2][$detail['fasilitas']]) ? $kriteria_nilai_map[2][$detail['fasilitas']] : 0;
    $c3 = $detail['biaya'] / 1000;
    $c4 = isset($kriteria_nilai_map[4][$detail['keamanan']]) ? $kriteria_nilai_map[4][$detail['keamanan']] : 0;

    // Hitung nilai S
    $nilai_s = pow($c1, $normalized_weights['C1']) *
               pow($c2, $normalized_weights['C2']) *
               pow($c3, $normalized_weights['C3']) *
               pow($c4, $normalized_weights['C4']);

    $vector_data[] = [
        'id_wisata' => $row['id_wisata'],
        'nama_wisata' => $row['nama_wisata'],
        'nilai_s' => $nilai_s
    ];
    
    $total_s += $nilai_s;
}

// Hitung nilai V dan simpan ke database
// Hapus data lama
mysqli_query($conn, "TRUNCATE TABLE hasil_seleksi");

// Hitung V dan simpan data baru
foreach ($vector_data as $data) {
    $nilai_s = number_format($data['nilai_s'], 2);
    $nilai_v = number_format($data['nilai_s'] / $total_s, 4);
    $sql_insert = "INSERT INTO hasil_seleksi (id_wisata, vektor_s, vektor_v) 
                   VALUES (
                       {$data['id_wisata']},
                       {$nilai_s},
                       {$nilai_v}
                   )";
    mysqli_query($conn, $sql_insert);
}

// Menambahkan peringkat berdasarkan nilai V
$sql_ranking = "SELECT id_wisata, vektor_s, vektor_v 
                FROM hasil_seleksi 
                ORDER BY vektor_v DESC";
$result_ranking = mysqli_query($conn, $sql_ranking);

if (!$result_ranking) {
    die("Query gagal untuk peringkat: " . mysqli_error($conn));
}

// Menyimpan peringkat ke dalam tabel hasil_seleksi
$rank = 1;
while ($row = mysqli_fetch_assoc($result_ranking)) {
    $sql_update_ranking = "UPDATE hasil_seleksi 
                           SET ranking = {$rank} 
                           WHERE id_wisata = {$row['id_wisata']}";
    mysqli_query($conn, $sql_update_ranking);
    $rank++;
}

// Mengambil data peringkat dan menampilkannya tanpa mengurutkan ulang data
$sql_ranking_display = "SELECT w.id_wisata, w.nama_wisata, h.vektor_s, h.vektor_v, h.ranking 
                        FROM hasil_seleksi h 
                        JOIN wisata w ON h.id_wisata = w.id_wisata 
                        ORDER BY h.id_wisata"; // Menambahkan id_wisata

$result_ranking_display = mysqli_query($conn, $sql_ranking_display);
if (!$result_ranking_display) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Vektor V</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-custom thead tr {
            background-color: #E6E6FA;
        }
        .table-custom tbody tr:nth-child(odd) {
            background-color: #E6E6FA;
        }
        .table-custom tbody tr:nth-child(even) {
            background-color: #FFFFFF;
        }
        .hasil-column {
            background-color: #90EE90 !important;
        }
    </style>
</head>
<body class="container py-4">
    <h1 class="text-center text-primary mb-4">Perhitungan Vektor V</h1>
    
    <div class="alert alert-info">
        <h5>Rumus : Vi = Si / ∑Si</h5>
        <h6>Total Nilai S (∑Si) = <?= number_format($total_s, 4) ?></h6>
        
        <h6>Penjelasan : </h6>
        <p>Vi : Nilai vektor V untuk alternatif i</p>
        <p>Si : Nilai vektor S untuk alternatif i</p>
        <p>∑Si : Total nilai S dari semua alternatif</p>
    </div>

    <table class="table table-bordered table-custom">
        <thead>
            <tr>
                <th>Alternatif V</th>
                <th>Nama Wisata</th>
                <th>Nilai Vektor S</th>
                <th class="hasil-column">Hasil Vektor V</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = mysqli_fetch_assoc($result_ranking_display)): ?>
                <tr>
                    <td>V<?= $row['id_wisata'] ?></td>  <!-- Memastikan id_wisata ada di query -->
                    <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                    <td><?= number_format($row['vektor_s'], 2) ?></td>
                    <td class="hasil-column"><?= number_format($row['vektor_v'], 4) ?></td>
                </tr>
            <?php endwhile; ?>

        </tbody>
    </table>

    <div class="mt-3">
        <a href="../index.php" class="btn btn-secondary">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
