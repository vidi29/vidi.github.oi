<?php
require_once 'Config.php'; // Koneksi ke database

// Ambil daftar tahun unik dari tabel nilai_daerah
$sql_tahun = "SELECT DISTINCT tahun FROM nilai_daerah ORDER BY tahun DESC";
$result_tahun = mysqli_query($conn, $sql_tahun);

if (!$result_tahun) {
    die("Query gagal: " . mysqli_error($conn));
}

// Ambil tahun yang dipilih dari request
$tahun_terpilih = isset($_GET['tahun']) ? $_GET['tahun'] : null;

// Query untuk mengambil data nilai_daerah berdasarkan tahun
$sql_nilai = "
    SELECT nd.id_nilai, d.nama_daerah, k.nama_kriteria, nd.nilai, nd.tahun
    FROM nilai_daerah nd
    JOIN daerah d ON nd.id_daerah = d.id_daerah
    JOIN kriteria k ON nd.id_kriteria = k.id_kriteria
";

if ($tahun_terpilih && $tahun_terpilih != "all") {
    $sql_nilai .= " WHERE nd.tahun = ?";
}

$sql_nilai .= " ORDER BY nd.id_nilai ASC";

$stmt = $conn->prepare($sql_nilai);

if ($tahun_terpilih && $tahun_terpilih != "all") {
    $stmt->bind_param('i', $tahun_terpilih);
}

$stmt->execute();
$result_nilai = $stmt->get_result();

if (!$result_nilai) {
    die("Query gagal: " . mysqli_error($conn));
}

$no_data = ($tahun_terpilih == 'all' || $result_nilai->num_rows == 0); // Menentukan apakah data ada atau tidak
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Daerah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="mb-4 text-center text-primary fw-bold border-bottom pb-2">Data Nilai Daerah</h1>

    <!-- Form Filter Tahun -->
    <form method="GET">
        <div class="mb-3">
            <label for="tahun" class="form-label">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-select">
                <option value="all" <?= !$tahun_terpilih || $tahun_terpilih == "all" ? 'selected' : '' ?>>Pilih Tahun</option>
                <?php while ($row = mysqli_fetch_assoc($result_tahun)): ?>
                    <option value="<?= $row['tahun'] ?>" <?= $tahun_terpilih == $row['tahun'] ? 'selected' : '' ?>>
                        <?= $row['tahun'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
<p></p>
    <!-- Tabel Data Nilai -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Daerah</th>
                <th>Nama Kriteria</th>
                <th>Nilai</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($no_data): ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data untuk tahun yang dipilih</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; ?>
                <?php while ($row = $result_nilai->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_daerah']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kriteria']) ?></td>
                        <td><?= number_format($row['nilai'], 2) ?></td>
                        <td><?= $row['tahun'] ?></td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="window.location.href='edit.php?id=<?= $row['id_nilai'] ?>'">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id_nilai'] ?>)">Hapus</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="../Index.php" class="btn btn-secondary">Kembali</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
