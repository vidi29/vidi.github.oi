<?php
$conn = mysqli_connect("localhost", "root", "", "spk_wisata");

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
