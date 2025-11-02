<?php
// 1. Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';

// Notifikasi (akan kita pakai nanti)
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = "";
    $alert_type = "alert-success"; // Default sukses

    if ($status == 'sukses_tambah') {
        $message = "Data hewan berhasil ditambahkan.";
    } else if ($status == 'sukses_update') {
        $message = "Data hewan berhasil diupdate.";
    } else if ($status == 'sukses_hapus') {
        $message = "Data hewan berhasil dihapus.";
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

// ==========================================================
// QUERY SQL BARU MENGGUNAKAN JOIN
// ==========================================================
// Kita ambil semua kolom dari 'pet' (p.*)
// DAN kita ambil 'nama_lengkap' dari tabel 'owner' (o.nama_lengkap)
// ... di mana pet.owner_id SAMA DENGAN owner.owner_id
$sql = "SELECT p.*, o.nama_lengkap 
        FROM pet p 
        JOIN owner o ON p.owner_id = o.owner_id 
        ORDER BY p.nama_hewan ASC";
// ==========================================================

$stmt = $pdo->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manajemen Hewan Peliharaan</h1>
    <a href="/pages/admin/pets/create.php" class="btn btn-primary">
        + Tambah Hewan Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Hewan</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Pemilik</th> <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Status
                    $status_text = $row['status'] == 'Aktif' ? 'Aktif' : 'Meninggal';
                    $status_class = $row['status'] == 'Aktif' ? 'bg-success' : 'bg-secondary';

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['pet_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ras'] ?? 'N/A') . "</td>";
                    // Tampilkan nama pemilik hasil JOIN
                    echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                    echo "<td><span class='badge " . $status_class . "'>" . $status_text . "</span></td>";
                    echo "<td>";
                    // Link Aksi (masih non-aktif)
                    echo "<a href='/pages/admin/pets/edit.php?id=" . htmlspecialchars($row['pet_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/admin/pets/delete.php?id=" . htmlspecialchars($row['pet_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Menghapus hewan akan menghapus semua riwayat medisnya.\\n\\nApakah Anda yakin?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">Belum ada data hewan.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>