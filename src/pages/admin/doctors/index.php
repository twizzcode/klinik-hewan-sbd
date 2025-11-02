<?php
// 1. Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';
// Ganti path ini jika config Anda tidak di level root src
include_once __DIR__ . '/../../../config/database.php'; 

$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM veterinarian ORDER BY nama_dokter ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manajemen Dokter</h1>
    <a href="/pages/admin/doctors/create.php" class="btn btn-primary">
        + Tambah Dokter Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama Dokter</th>
                <th>Spesialisasi</th>
                <th>No. Telepon</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['dokter_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_dokter']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['spesialisasi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['no_telepon']) . "</td>";
                    echo "<td><span class='badge bg-success'>" . htmlspecialchars($row['status']) . "</span></td>";
                    echo "<td>";
                    echo "<a href='/pages/admin/doctors/edit.php?id=" . htmlspecialchars($row['dokter_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/admin/doctors/delete.php?id=" . htmlspecialchars($row['dokter_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus dokter ini?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">Belum ada data dokter.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// 7. Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>