<?php
// 1. Sertakan header dan koneksi
include_once __DIR__ . '/../../layouts/admin_header.php';

// Notifikasi (akan kita pakai nanti)
if (isset($_GET['status'])) {
    // ... (Tambahkan kode notifikasi dari modul lain nanti) ...
}

include_once __DIR__ . '/../../../config/database.php'; 

$pdo = getDBConnection();

// ==========================================================
// QUERY SQL DENGAN MULTI-JOIN
// ==========================================================
// Kita ingin:
// 1. Nama Hewan (dari tabel 'pet')
// 2. Nama Pemilik (dari tabel 'owner')
// 3. Nama Dokter (dari tabel 'veterinarian')
// 4. Info Appointment (dari tabel 'appointment')

$sql = "SELECT 
            a.appointment_id, 
            a.tanggal_appointment, 
            a.jam_appointment, 
            a.status,
            p.nama_hewan,
            o.nama_lengkap AS nama_pemilik,
            v.nama_dokter
        FROM 
            appointment a
        JOIN 
            pet p ON a.pet_id = p.pet_id
        JOIN 
            owner o ON a.owner_id = o.owner_id
        JOIN 
            veterinarian v ON a.dokter_id = v.dokter_id
        ORDER BY 
            a.tanggal_appointment DESC, a.jam_appointment ASC";
// ==========================================================

$stmt = $pdo->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manajemen Janji Temu (Appointment)</h1>
    <a href="/pages/admin/appointments/create.php" class="btn btn-primary">
        + Buat Appointment Baru
    </a>
</div>



<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Nama Hewan</th>
                <th>Nama Pemilik</th>
                <th>Dokter</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Format Tanggal & Jam
                    $tanggal = date('d M Y', strtotime($row['tanggal_appointment']));
                    $jam = date('H:i', strtotime($row['jam_appointment']));
                    
                    // Styling Status
                    $status = htmlspecialchars($row['status']);
                    $status_class = 'bg-secondary'; // Default
                    if ($status == 'Pending') $status_class = 'bg-warning text-dark';
                    if ($status == 'Confirmed') $status_class = 'bg-info text-dark';
                    if ($status == 'Completed') $status_class = 'bg-success';
                    if ($status == 'Cancelled') $status_class = 'bg-danger';

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['appointment_id']) . "</td>";
                    echo "<td>" . $tanggal . "</td>";
                    echo "<td>" . $jam . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_pemilik']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_dokter']) . "</td>";
                    echo "<td><span class='badge " . $status_class . "'>" . $status . "</span></td>";
                    echo "<td>";
                    // Link Aksi (masih non-aktif)
                    echo "<a href='/pages/admin/appointments/edit.php?id=" . htmlspecialchars($row['appointment_id']) . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                    echo "<a href='/actions/admin/appointments/delete.php?id=" . htmlspecialchars($row['appointment_id']) . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus janji temu ini?\");'>Hapus</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="8" class="text-center">Belum ada data appointment.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Sertakan footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>