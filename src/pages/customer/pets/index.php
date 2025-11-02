<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/../layouts/header.php';
// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil ID user dari Session (ini bagian penting!)
$owner_id = $_SESSION['user_id'];

$pdo = getDBConnection();

// 4. Query HANYA untuk pet milik user ini (WHERE owner_id = ?)
$sql = "SELECT * FROM pet WHERE owner_id = ? ORDER BY nama_hewan ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$owner_id]); // Amankan query dengan prepared statement
?>

<?php
if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = "";
    $alert_type = "alert-success"; // Default sukses

    if ($status == 'sukses_tambah') {
        $message = "Data hewan baru berhasil ditambahkan.";
    } else if ($status == 'sukses_update') {
        $message = "Data hewan berhasil diupdate.";
    } else if ($status == 'sukses_hapus') {
        $message = "Data hewan berhasil dihapus.";
    } else if ($status == 'gagal_hapus') {
        $message = $_GET['error'] ?? "Gagal menghapus hewan.";
        $alert_type = "alert-danger";
    }
    
    if ($message) {
        echo "<div class='alert $alert_type alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Hewan Peliharaan Saya</h1>
    <a href="/pages/customer/pets/create.php" class="btn btn-primary">
        + Tambah Hewan Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>Nama Hewan</th>
                <th>Jenis</th>
                <th>Ras</th>
                <th>Jenis Kelamin</th>
                <th>Tgl Lahir</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_text = $row['status'] == 'Aktif' ? 'Aktif' : 'Meninggal';
                    $status_class = $row['status'] == 'Aktif' ? 'bg-success' : 'bg-secondary';
                    $tgl_lahir = $row['tanggal_lahir'] ? date('d M Y', strtotime($row['tanggal_lahir'])) : 'N/A';

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jenis']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ras'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>";
                    echo "<td>" . $tgl_lahir . "</td>";
                    echo "<td><span class='badge " . $status_class . "'>" . $status_text . "</span></td>";
                    echo "<td>";
                    // Link Aksi (akan kita buat nanti)
                    echo "<a href='/pages/customer/pets/edit.php?id=" . htmlspecialchars($row['pet_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/customer/pets/delete.php?id=" . htmlspecialchars($row['pet_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">Anda belum mendaftarkan hewan peliharaan.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// 5. Panggil footer
include_once __DIR__ . '/../layouts/footer.php';
?>