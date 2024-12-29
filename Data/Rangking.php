<?php
require_once '../Config.php';

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
    <title>Hasil Akhir Perangkingan</title>
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
    <h1 class="text-center text-primary mb-4">Hasil Akhir Perangkingan</h1>

    <table class="table table-bordered table-custom">
        <thead>
            <tr>
                <th>Nama Wisata</th>
                <th>Nilai Vektor_S</th>
                <th>Nilai Vektor_V</th>
                <th class="hasil-column">Peringkat</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            

            while ($row = mysqli_fetch_assoc($result_ranking_display)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                    <td><?= number_format($row['vektor_s'], 2) ?></td>
                    <td><?= number_format($row['vektor_v'], 4) ?></td>
                    <td class="hasil-column"><?= $row['ranking'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="../index.php" class="btn btn-secondary">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">Cetak</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
