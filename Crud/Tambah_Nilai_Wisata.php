<?php
session_start();
require_once '../Config.php';

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_wisata = $_POST['nama_wisata'];
    $lokasi = $_POST['lokasi'];
    $fasilitas = $_POST['fasilitas'];
    $biaya = $_POST['biaya'];
    $keamanan = $_POST['keamanan'];

    // Validasi input
    if (!empty($nama_wisata) && !empty($lokasi) && !empty($fasilitas) && is_numeric($biaya) && !empty($keamanan)) {
        // Query untuk menambahkan data
        $sql = "INSERT INTO wisata (nama_wisata, lokasi, fasilitas, biaya, keamanan) 
                VALUES ('$nama_wisata', '$lokasi', '$fasilitas', $biaya, '$keamanan')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Data wisata berhasil ditambahkan!');
                    window.location.href = '../Data/Data_Wisata.php';
                </script>";
        } else {
            echo "<script>
                    alert('Gagal menambahkan data: ');
                    window.location.href = 'Tambah_Nilai_Wisata.php';
                </script>";
        }
    } else {
        echo "<script>
                    alert('Data tidak valid. Mohon isi semua kolom.');
                    window.location.href = 'Tambah_Nilai_Wisata.php';
                </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1>Tambah Data Wisata</h1>

    <form method="POST" action="" class="needs-validation" novalidate>
        <!-- Nama Wisata -->
        <div class="mb-3">
            <label for="nama_wisata" class="form-label">Nama Wisata</label>
            <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" required>
        </div>

        <!-- Lokasi -->
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <select class="form-select" id="lokasi" name="lokasi" required>
                <option value="">Pilih Lokasi</option>
                <option value="Sangat Strategis">Sangat Strategis</option>
                <option value="Strategis">Strategis</option>
                <option value="Cukup Strategis">Cukup Strategis</option>
                <option value="Kurang Strategis">Kurang Strategis</option>
            </select>
        </div>

        <!-- Fasilitas -->
        <div class="mb-3">
            <label for="fasilitas" class="form-label">Fasilitas</label>
            <select class="form-select" id="fasilitas" name="fasilitas" required>
                <option value="">Pilih Fasilitas</option>
                <option value="4 Fasilitas">4 Fasilitas</option>
                <option value="3 Fasilitas">3 Fasilitas</option>
                <option value="2 Fasilitas">2 Fasilitas</option>
                <option value="1 Fasilitas">1 Fasilitas</option>
            </select>
        </div>

        <!-- Biaya -->
        <div class="mb-3">
            <label for="biaya" class="form-label">Biaya</label>
            <input type="number" class="form-control" id="biaya" name="biaya" required>
        </div>

        <!-- Keamanan -->
        <div class="mb-3">
            <label for="keamanan" class="form-label">Keamanan</label>
            <select class="form-select" id="keamanan" name="keamanan" required>
                <option value="">Pilih Keamanan</option>
                <option value="Security, Rak Loker, CCTV">Security, Rak Loker, CCTV</option>
                <option value="Security, Rak Loker">Security, Rak Loker</option>
                <option value="Security">Security</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Tambah Wisata</button>
        <a href="../Data/Data_Wisata.php" class="btn btn-secondary">Kembali</a>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
