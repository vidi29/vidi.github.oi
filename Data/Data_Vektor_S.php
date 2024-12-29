<?php
require_once '../Config.php';

// Ubah query untuk mengurutkan secara ascending
$sql_wisata = "SELECT * FROM wisata ORDER BY id_wisata ASC";
$result_wisata = mysqli_query($conn, $sql_wisata);

$sql_kriteria_nilai = "SELECT * FROM kriteria_nilai";
$result_kriteria_nilai = mysqli_query($conn, $sql_kriteria_nilai);

if (!$result_wisata || !$result_kriteria_nilai) {
    die("Query gagal: " . mysqli_error($conn));
}

// Mapping nilai kriteria
$kriteria_nilai_map = [];
while ($row = mysqli_fetch_assoc($result_kriteria_nilai)) {
    $kriteria_nilai_map[$row['id_kriteria']][$row['kinerja']] = $row['nilai_kriteria'];
}

// Ambil bobot normalisasi dari kriteria
$sql_kriteria = "SELECT * FROM kriteria";
$result_kriteria = mysqli_query($conn, $sql_kriteria);
$normalized_weights = [];
while ($row = mysqli_fetch_assoc($result_kriteria)) {
    $bobot = $row['bobot'] / 100;
    $normalized_weights['C' . $row['id_kriteria']] = $row['keterangan'] === 'Cost' ? -$bobot : $bobot;
}

// Hitung Vektor S untuk setiap alternatif
$vector_s = [];
mysqli_data_seek($result_wisata, 0);
while ($row = mysqli_fetch_assoc($result_wisata)) {
    $c1 = isset($kriteria_nilai_map[1][$row['lokasi']]) ? $kriteria_nilai_map[1][$row['lokasi']] : 0;
    $c2 = isset($kriteria_nilai_map[2][$row['fasilitas']]) ? $kriteria_nilai_map[2][$row['fasilitas']] : 0;
    $c3 = $row['biaya'] / 1000;
    $c4 = isset($kriteria_nilai_map[4][$row['keamanan']]) ? $kriteria_nilai_map[4][$row['keamanan']] : 0;

    // Hitung S = (C1^w1) × (C2^w2) × (C3^w3) × (C4^w4)
    $c1_pangkat = pow($c1, $normalized_weights['C1']);
    $c2_pangkat = pow($c2, $normalized_weights['C2']);
    $c3_pangkat = pow($c3, $normalized_weights['C3']);
    $c4_pangkat = pow($c4, $normalized_weights['C4']);
    
    // Menghitung nilai S
    $s = $c1_pangkat * $c2_pangkat * $c3_pangkat * $c4_pangkat;

    // Menyimpan hasil dalam array
    $vector_s[] = [
        'id_wisata' => $row['id_wisata'],
        'c1' => $c1_pangkat,
        'c2' => $c2_pangkat,
        'c3' => $c3_pangkat,
        'c4' => $c4_pangkat,
        'nilai_s' => $s
    ];
}

// Proses penyimpanan hasil ke dalam tabel 'hasil_seleksi' jika tombol simpan ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus semua data yang ada di tabel hasil_seleksi
    mysqli_query($conn, "TRUNCATE TABLE hasil_seleksi");
    
    // Reset Auto Increment
    mysqli_query($conn, "ALTER TABLE hasil_seleksi AUTO_INCREMENT = 1");
    
    foreach ($vector_s as $index => $alt) {
        $id_wisata = $index + 1; // Menggunakan index+1 untuk memastikan urutan 1,2,3,...
        $vektor_s = number_format($alt['nilai_s'], 2);
        $vektor_v = 0;
        $ranking = 0;

        // Insert data baru dengan id_hasil yang berurutan
        $sql_insert = "INSERT INTO hasil_seleksi (id_wisata, vektor_s, vektor_v, ranking)
                       VALUES ('$id_wisata', '$vektor_s', '$vektor_v', '$ranking')";
        if (!mysqli_query($conn, $sql_insert)) {
            die("Error: " . mysqli_error($conn));
        }
    }
    echo "<script>alert('Data berhasil disimpan!'); window.location.href='Data_Vektor_S.php';</script>";
}

// Tampilkan data sesuai urutan
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Vektor S</title>
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
    <h1 class="text-center text-primary mb-4">Perhitungan Vektor S</h1>

    <div class="alert alert-info">
        <h5>Rumus: Si = C1^w1 x C1^w2 x ... x Cn^wn</h5>
        <h6>Penjelasan:</h6>
        <p>Si : Nilai vektor S untuk alternatif i</p>
        <p>Cn : Nilai-nilai kriteria untuk alternatif i</p>
        <p>wn : Bobot-bobot untuk masing-masing kriteria</p>
    </div>

    <table class="table table-bordered table-custom">
        <thead>
            <tr>
                <th>Alternatif S</th>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
                <th>C4</th>
                <th class="hasil-column">Hasil Vektor S</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vector_s as $index => $alt): ?>
                <tr>
                    <td>S<?= $index + 1 ?></td>
                    <td><?= number_format($alt['c1'], 2) ?></td>
                    <td><?= number_format($alt['c2'], 2) ?></td>
                    <td><?= number_format($alt['c3'], 2) ?></td>
                    <td><?= number_format($alt['c4'], 2) ?></td>
                    <td class="hasil-column"><?= number_format($alt['nilai_s'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <form method="POST">
            <button type="submit" class="btn btn-success">Simpan Nilai Vektor S</button>
            <a href="Data_Vektor_V.php" class="btn btn-primary">Lanjut</a>
            <a href="../index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>