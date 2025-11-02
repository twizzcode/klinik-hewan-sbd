<?php
// 1. Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php'; 

$pdo = getDBConnection();
// GANTI: Query ke tabel 'service'
$stmt = $pdo->query("SELECT * FROM service ORDER BY nama_layanan ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manajemen Layanan</h1>
    <a href="/pages/admin/services/create.php" class="btn btn-primary">
        + Tambah Layanan Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Layanan</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Durasi (Menit)</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Siapkan format harga
                    $harga_rupiah = "Rp " . number_format($row['harga'], 0, ',', '.');
                    // Siapkan status
                    $status_text = $row['status_tersedia'] ? 'Tersedia' : 'Tidak Tersedia';
                    $status_class = $row['status_tersedia'] ? 'bg-success' : 'bg-danger';

                    echo "<tr>";
                    // GANTI: Data kolom
                    echo "<td>" . htmlspecialchars($row['layanan_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_layanan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                    echo "<td>" . htmlspecialchars($harga_rupiah) . "</td>";
                    echo "<td>" . htmlspecialchars($row['durasi_estimasi']) . "</td>";
                    echo "<td><span class='badge " . $status_class . "'>" . $status_text . "</span></td>";
                    echo "<td>";
                    // GANTI: Link Aksi (masih non-aktif)
                    echo "<a href='/pages/admin/services/edit.php?id=" . htmlspecialchars($row['layanan_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/admin/services/delete.php?id=" . htmlspecialchars($row['layanan_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus layanan ini?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">Belum ada data layanan.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// 7. Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>