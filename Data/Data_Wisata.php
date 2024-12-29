<?php
require_once '../Config.php'; // Koneksi ke database

// Query untuk mengambil data dari tabel wisata
$sql_wisata = "SELECT * FROM wisata";
$result_wisata = mysqli_query($conn, $sql_wisata);

if (!$result_wisata) {
    die("Query gagal: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus wisata ini?")) {
                window.location.href = "../Crud/Hapus_Wisata.php?id=" + id;
            }
        }
    </script>
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center text-primary fw-bold border-bottom pb-2">
        <i class="bi bi-list-task">Data wisata</i> 
    </h1>
    <div class="mb-4">
    <button class="btn btn-primary btn-sm" onclick="window.location.href='../Crud/Tambah_Nilai_Wisata.php'">Tambah wisata</button>
    </div>
    <div class="mb-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama wisata</th>
                    <th>Lokasi</th>
                    <th>Fasilitas</th>
                    <th>Biaya</th>
                    <th>Keamanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result_wisata)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                    <td><?= htmlspecialchars($row['lokasi'])?></td>
                    <td><?= htmlspecialchars($row['fasilitas'])?></td>
                    <td>Rp <?= number_format($row['biaya'], 2)?></td>
                    <td><?= htmlspecialchars($row['keamanan'])?></td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="window.location.href='../Crud/Edit_Wisata.php?id=<?= $row['id_wisata'] ?>'">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id_wisata'] ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="../Index.php" class="btn btn-secondary">Kembali</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

