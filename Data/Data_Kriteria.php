<?php
require_once '../Config.php'; // Koneksi ke database

// Query untuk mengambil data dari tabel kriteria
$sql_kriteria = "SELECT id_kriteria, nama_kriteria, bobot, keterangan FROM kriteria";
$result_kriteria = mysqli_query($conn, $sql_kriteria);

if (!$result_kriteria) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria dan Bobot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center text-primary fw-bold border-bottom pb-2">
        <i class="bi bi-list-task">Data Kriteria dan Bobot</i> 
    </h1>
    
    <div class="mb-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kriteria</th>
                    <th>Bobot</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result_kriteria)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
                    <td><?= number_format($row['bobot'], 0) ?>%</td>
                    <td><?= $row['keterangan'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="../Index.php" class="btn btn-secondary">Kembali</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>