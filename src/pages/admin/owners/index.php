<?php
// 1. Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';

// Notifikasi (akan kita pakai nanti)
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = "";
    $alert_type = "alert-success"; // Default sukses

    if ($status == 'sukses_tambah') {
        $message = "Data pemilik berhasil ditambahkan.";
    } else if ($status == 'sukses_update') {
        $message = "Data pemilik berhasil diupdate.";
    } else if ($status == 'sukses_hapus') {
        $message = "Data pemilik berhasil dihapus.";
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
// GANTI: Query ke tabel 'owner'
$stmt = $pdo->query("SELECT * FROM owner ORDER BY nama_lengkap ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manajemen Pemilik (Owner)</h1>
    <a href="/pages/admin/owners/create.php" class="btn btn-primary">
        + Tambah Pemilik Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Tgl Registrasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Format Tanggal
                    $tgl_registrasi = date('d M Y', strtotime($row['tanggal_registrasi']));

                    echo "<tr>";
                    // GANTI: Data kolom
                    echo "<td>" . htmlspecialchars($row['owner_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['no_telepon']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email'] ?? 'N/A') . "</td>"; // Pakai '??' untuk handle NULL
                    echo "<td>" . htmlspecialchars($row['alamat'] ?? 'N/A') . "</td>";
                    echo "<td>" . $tgl_registrasi . "</td>";
                    echo "<td>";
                    // Link Aksi (masih non-aktif)
                    echo "<a href='/pages/admin/owners/edit.php?id=" . htmlspecialchars($row['owner_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/admin/owners/delete.php?id=" . htmlspecialchars($row['owner_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"PERHATIAN:\\nMenghapus pemilik akan menghapus SEMUA data hewan dan janji temu yang terkait.\\n\\nApakah Anda yakin?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">Belum ada data pemilik.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>