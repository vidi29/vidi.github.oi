<?php
require_once '../Config.php'; // Koneksi ke database

// Ambil data kriteria untuk normalisasi bobot
$sql_kriteria = "SELECT * FROM kriteria";
$result_kriteria = mysqli_query($conn, $sql_kriteria);

if (!$result_kriteria) {
    die("Query gagal: " . mysqli_error($conn));
}

// Normalisasi bobot menjadi eksponen
$normalized_weights = [];
$kriteria_data = []; // Array tambahan untuk menyimpan data kriteria

while ($row = mysqli_fetch_assoc($result_kriteria)) {
    $bobot = $row['bobot'] / 100; // Konversi bobot ke desimal
    $normalized_weights['C' . $row['id_kriteria']] = $row['keterangan'] === 'Cost' ? -$bobot : $bobot;
    $kriteria_data[] = $row; // Simpan data untuk tabel HTML
}

// var_dump($normalized_weights);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normalisasi Bobot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="text-center text-primary mb-4">Normalisasi Bobot (Pangkat)</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Kriteria</th>
                <th>Nama Kriteria</th>
                <th>Bobot Asli</th>
                <th>Keterangan</th>
                <th>Bobot Normalisasi (Pangkat)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kriteria_data as $row): ?>
                <tr>
                    <td>C<?= $row['id_kriteria'] ?></td>
                    <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
                    <td><?= htmlspecialchars($row['bobot']) ?>%</td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td><?= number_format($normalized_weights['C' . $row['id_kriteria']], 1) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="Data_Konversi.php" class="btn btn-secondary">Kembali</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
