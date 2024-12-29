<?php
require_once '../Config.php'; // Koneksi ke database

// Kriteria
$kriteria = [1 => 'Lokasi', 2 => 'Fasilitas', 3 => 'Biaya', 4 => 'Keamanan'];

// Menyiapkan array untuk data
$data_kriteria = [];

foreach ($kriteria as $id_kriteria => $nama_kriteria) {
    // Query untuk mengambil data berdasarkan kriteria
    $sql = "SELECT 
                kriteria.nama_kriteria AS nama_kriteria,
                kriteria_nilai.kinerja AS kinerja,
                kriteria_nilai.nilai_kriteria AS nilai_kriteria
            FROM 
                kriteria_nilai
            JOIN 
                kriteria 
            ON 
                kriteria_nilai.id_kriteria = kriteria.id_kriteria
            WHERE 
                kriteria.id_kriteria = $id_kriteria";  // Filter berdasarkan id_kriteria

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    // Menyimpan data dalam array
    $data_kriteria[$id_kriteria] = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria Nilai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center text-primary fw-bold border-bottom pb-2">
        <i class="bi bi-list-task">Data Kriteria Nilai</i>
    </h1>

    <?php foreach ($data_kriteria as $id_kriteria => $kriteria_data): ?>
        <h2 class="text-success text-center">Data Kriteria - <?= htmlspecialchars($kriteria[$id_kriteria]) ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kinerja</th>
                    <th>Nilai Kriteria</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($kriteria_data as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kinerja']) ?></td>
                        <td><?= htmlspecialchars($row['nilai_kriteria']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>

    <a href="../Index.php" class="btn btn-secondary">Kembali</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
