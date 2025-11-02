<?php
// 1. Sertakan header admin
include_once __DIR__ . '/../../layouts/admin_header.php';

if (isset($_GET['status']) && $_GET['status'] == 'sukses_bayar') {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Pembayaran Berhasil!</strong> Transaksi telah dicatat dan ditandai lunas.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}

include_once __DIR__ . '/../../../config/database.php';


$pdo = getDBConnection();

// ==========================================================
// 2. QUERY SEMUA APPOINTMENT YANG 'COMPLETED'
// ==========================================================
// Kita JOIN 4 tabel untuk dapat info lengkap
$sql = "SELECT 
            a.appointment_id, 
            a.tanggal_appointment,
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
        WHERE 
            a.status = 'Completed' -- HANYA tampilkan yang siap bayar
        ORDER BY 
            a.tanggal_appointment DESC, a.jam_appointment DESC";
// ==========================================================

$stmt = $pdo->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Antrian Kasir / Tagihan</h1>
    <a href="#" class="btn btn-outline-success">
        Riwayat Transaksi
    </a>
</div>
<p class="lead">Daftar janji temu yang telah selesai (Completed) dan siap untuk pembayaran.</p>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID App.</th>
                        <th>Tanggal</th>
                        <th>Nama Hewan</th>
                        <th>Nama Pemilik</th>
                        <th>Dokter</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $tanggal = date('d M Y', strtotime($row['tanggal_appointment']));
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['appointment_id']) . "</td>";
                            echo "<td>" . $tanggal . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_pemilik']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_dokter']) . "</td>";
                            echo "<td>";
                            // Ini adalah tombol PENTING untuk langkah selanjutnya
                            echo "<a href='/pages/admin/billing/view.php?appointment_id=" . htmlspecialchars($row['appointment_id']) . "' class='btn btn-primary'>
                                    Proses Bayar / Lihat Tagihan
                                  </a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center">Tidak ada antrian pembayaran.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// 4. Panggil footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>