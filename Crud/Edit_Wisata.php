<?php
require_once '../Config.php'; // Koneksi ke database

// Cek apakah ID wisata diterima dari URL
if (isset($_GET['id'])) {
    $id_wisata = $_GET['id'];

    // Query untuk mengambil data wisata berdasarkan ID
    $sql = "SELECT * FROM wisata WHERE id_wisata = $id_wisata";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
} else {
    die("ID wisata tidak ditemukan.");
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_wisata = $_POST['nama_wisata'];
    $lokasi = $_POST['lokasi'];
    $fasilitas = $_POST['fasilitas'];
    $biaya = $_POST['biaya'];
    $keamanan = $_POST['keamanan'];

    // Query untuk mengupdate data wisata
    $sql_update = "UPDATE wisata SET 
                   nama_wisata = '$nama_wisata',
                   lokasi = '$lokasi',
                   fasilitas = '$fasilitas',
                   biaya = '$biaya',
                   keamanan = '$keamanan' 
                   WHERE id_wisata = $id_wisata";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('Data wisata berhasil diperbarui!'); window.location.href = '../Data/Data_Wisata.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="text-center text-primary mb-4">Edit Data Wisata</h1>

    <form method="POST">
        <div class="mb-3">
            <label for="nama_wisata" class="form-label">Nama Wisata</label>
            <input type="text" class="form-control" id="nama_wisata" name="nama_wisata" value="<?= htmlspecialchars($row['nama_wisata']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= htmlspecialchars($row['lokasi']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="fasilitas" class="form-label">Fasilitas</label>
            <input type="text" class="form-control" id="fasilitas" name="fasilitas" value="<?= htmlspecialchars($row['fasilitas']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="biaya" class="form-label">Biaya</label>
            <input type="number" class="form-control" id="biaya" name="biaya" value="<?= htmlspecialchars($row['biaya']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="keamanan" class="form-label">Keamanan</label>
            <input type="text" class="form-control" id="keamanan" name="keamanan" value="<?= htmlspecialchars($row['keamanan']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui Data</button>
        <a href="../Data/Data_Wisata.php" class="btn btn-secondary">Kembali</a>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
