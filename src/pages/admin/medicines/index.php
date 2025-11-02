<?php
// 1. Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';

// Notifikasi (copy dari services/index.php, akan kita pakai nanti)
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = "";
    $alert_type = "alert-success"; // Default sukses

    if ($status == 'sukses_tambah') {
        $message = "Data obat berhasil ditambahkan.";
    } else if ($status == 'sukses_update') {
        $message = "Data obat berhasil diupdate.";
    } else if ($status == 'sukses_hapus') {
        $message = "Data obat berhasil dihapus.";
    } else if ($status == 'gagal_hapus') {
        $message = $_GET['error'] ?? "Gagal menghapus data.";
        $alert_type = "alert-danger";
    }
    
    if ($message) {
        echo "<div class='alert $alert_type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

include_once __DIR__ . '/../../../config/database.php'; 

$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM medicine ORDER BY nama_obat ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manajemen Obat & Inventaris</h1>
    <a href="/pages/admin/medicines/create.php" class="btn btn-primary">
        + Tambah Obat Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga Jual</th>
                <th>Expired</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Format Harga Jual
                    $harga_jual = "Rp " . number_format($row['harga_jual'], 0, ',', '.');
                    
                    // Cek Stok (untuk styling)
                    $stok_class = '';
                    if ($row['stok'] <= 10) $stok_class = 'text-danger fw-bold'; // Stok menipis
                    if ($row['stok'] == 0) $stok_class = 'text-danger bg-light fw-bold'; // Stok habis

                    // Cek Expired Date
                    $expired_text = $row['expired_date'] ? date('d M Y', strtotime($row['expired_date'])) : 'N/A';
                    $expired_class = '';
                    if ($row['expired_date'] && strtotime($row['expired_date']) < time()) {
                        $expired_class = 'text-danger fw-bold'; // Sudah expired
                        $expired_text .= " (Expired)";
                    }

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['obat_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_obat']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
                    echo "<td class='" . $stok_class . "'>" . htmlspecialchars($row['stok']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['satuan']) . "</td>";
                    echo "<td>" . $harga_jual . "</td>";
                    echo "<td class='" . $expired_class . "'>" . $expired_text . "</td>";
                    echo "<td>";
                    // Link Aksi (masih non-aktif)
                    echo "<a href='/pages/admin/medicines/edit.php?id=" . htmlspecialchars($row['obat_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/admin/medicines/delete.php?id=" . htmlspecialchars($row['obat_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus obat ini?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="8" class="text-center">Belum ada data obat.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>