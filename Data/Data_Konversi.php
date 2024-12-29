<?php
require_once '../Config.php'; // Koneksi ke database

// Ambil data wisata dan kriteria
$sql_wisata = "SELECT * FROM wisata";
$result_wisata = mysqli_query($conn, $sql_wisata);

$sql_kriteria_nilai = "SELECT * FROM kriteria_nilai";
$result_kriteria_nilai = mysqli_query($conn, $sql_kriteria_nilai);

if (!$result_wisata || !$result_kriteria_nilai) {
    die("Query gagal: " . mysqli_error($conn));
}

// Mapping nilai kriteria berdasarkan id_kriteria dan kinerja
$kriteria_nilai_map = [];
while ($row = mysqli_fetch_assoc($result_kriteria_nilai)) {
    $kriteria_nilai_map[$row['id_kriteria']][$row['kinerja']] = $row['nilai_kriteria'];
}

// Proses konversi nilai
$alternatives = [];
while ($row = mysqli_fetch_assoc($result_wisata)) {
    $alternatives[] = [
        'alternatif' => 'A' . $row['id_wisata'],
        'C1' => isset($kriteria_nilai_map[1][$row['lokasi']]) ? $kriteria_nilai_map[1][$row['lokasi']] : 0,
        'C2' => isset($kriteria_nilai_map[2][$row['fasilitas']]) ? $kriteria_nilai_map[2][$row['fasilitas']] : 0,
        'C3' => $row['biaya'] / 1000,
        'C4' => isset($kriteria_nilai_map[4][$row['keamanan']]) ? $kriteria_nilai_map[4][$row['keamanan']] : 0
    ];
}
// var_dump($alternatives)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konversi Nilai Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="text-center text-primary mb-4">Konversi Nilai Alternatif</h1>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Alternatif</th>
                <th>C1 (Lokasi)</th>
                <th>C2 (Fasilitas)</th>
                <th>C3 (Biaya)</th>
                <th>C4 (Keamanan)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatives as $alt): ?>
                <tr>
                    <td><?= htmlspecialchars($alt['alternatif']) ?></td>
                    <td><?= htmlspecialchars($alt['C1']) ?></td>
                    <td><?= htmlspecialchars($alt['C2']) ?></td>
                    <td><?= htmlspecialchars($alt['C3']) ?></td>
                    <td><?= htmlspecialchars($alt['C4']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="Data_Normalisasi.php" class="btn btn-success">Lanjut</a>
    <a href="../index.php" class="btn btn-secondary">Kembali</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
