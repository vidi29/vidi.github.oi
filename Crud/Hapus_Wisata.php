<?php
require_once '../Config.php'; // Koneksi ke database

// Cek apakah ID wisata diterima dari URL
if (isset($_GET['id'])) {
    $id_wisata = $_GET['id'];

    // Query untuk menghapus data wisata berdasarkan ID
    $sql = "DELETE FROM wisata WHERE id_wisata = $id_wisata";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data wisata berhasil dihapus!'); window.location.href = '../Crud/Data_Wisata.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    die("ID wisata tidak ditemukan.");
}
?>
