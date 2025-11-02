<?php
// 1. Sertakan header admin (yang sudah ada satpam-nya)
include_once __DIR__ . '/../../layouts/admin_header.php';
include_once __DIR__ . '/../../../config/database.php';

// 2. Ambil ID Appointment dari URL
if (!isset($_GET['appointment_id'])) {
    echo "<div class='alert alert-danger'>ID Appointment tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}
$appointment_id = $_GET['appointment_id'];

$pdo = getDBConnection();

// ==========================================================
// 1. AMBIL INFO DASAR APPOINTMENT
// ==========================================================
$sql_app = "SELECT 
                a.tanggal_appointment, p.nama_hewan, o.nama_lengkap AS nama_pemilik, v.nama_dokter, mr.rekam_id
            FROM 
                appointment a
            JOIN 
                pet p ON a.pet_id = p.pet_id
            JOIN 
                owner o ON a.owner_id = o.owner_id
            JOIN 
                veterinarian v ON a.dokter_id = v.dokter_id
            LEFT JOIN 
                medical_record mr ON a.appointment_id = mr.appointment_id
            WHERE 
                a.appointment_id = ?";
$stmt_app = $pdo->prepare($sql_app);
$stmt_app->execute([$appointment_id]);
$appointment = $stmt_app->fetch(PDO::FETCH_ASSOC);

if (!$appointment) {
    echo "<div class='alert alert-danger'>Data appointment tidak ditemukan.</div>";
    include_once __DIR__ . '/../../layouts/admin_footer.php';
    exit;
}
$rekam_id = $appointment['rekam_id']; // Kita butuh ini untuk cari resep

// ==========================================================
// 2. AMBIL SEMUA LAYANAN YANG DIBERIKAN
// ==========================================================
$sql_services = "SELECT s.nama_layanan, al.harga_satuan, al.jumlah, al.subtotal
                 FROM appointment_layanan al
                 JOIN service s ON al.layanan_id = s.layanan_id
                 WHERE al.appointment_id = ?";
$stmt_services = $pdo->prepare($sql_services);
$stmt_services->execute([$appointment_id]);
$total_layanan = 0;

// ==========================================================
// 3. AMBIL SEMUA OBAT DARI RESEP
// ==========================================================
$sql_medicines = "SELECT m.nama_obat, r.dosis, r.jumlah, r.harga_satuan, r.subtotal
                  FROM resep r
                  JOIN medicine m ON r.obat_id = m.obat_id
                  WHERE r.rekam_id = ?";
$stmt_medicines = $pdo->prepare($sql_medicines);
$stmt_medicines->execute([$rekam_id]);
$total_obat = 0;
?>

<h1>Detail Tagihan (Invoice)</h1>
<p>
    <strong>ID Janji Temu:</strong> #<?php echo $appointment_id; ?> <br>
    <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($appointment['tanggal_appointment'])); ?>
</p>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Rincian Layanan / Jasa</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama Layanan</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($stmt_services->rowCount() > 0) {
                            while ($item = $stmt_services->fetch(PDO::FETCH_ASSOC)) {
                                $total_layanan += $item['subtotal'];
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($item['nama_layanan']) . "</td>";
                                echo "<td>Rp " . number_format($item['harga_satuan'], 0, ',', '.') . "</td>";
                                echo "<td>" . htmlspecialchars($item['jumlah']) . "</td>";
                                echo "<td class='text-end'>Rp " . number_format($item['subtotal'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">Tidak ada layanan.</td></tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot class="fw-bold">
                        <tr>
                            <td colspan="3" class="text-end">Total Biaya Layanan:</td>
                            <td class="text-end">Rp <?php echo number_format($total_layanan, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Rincian Obat (Resep)</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($stmt_medicines->rowCount() > 0) {
                            while ($item = $stmt_medicines->fetch(PDO::FETCH_ASSOC)) {
                                $total_obat += $item['subtotal'];
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($item['nama_obat']) . "</td>";
                                echo "<td>Rp " . number_format($item['harga_satuan'], 0, ',', '.') . "</td>";
                                echo "<td>" . htmlspecialchars($item['jumlah']) . "</td>";
                                echo "<td class='text-end'>Rp " . number_format($item['subtotal'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">Tidak ada resep obat.</td></tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot class="fw-bold">
                        <tr>
                            <td colspan="3" class="text-end">Total Biaya Obat:</td>
                            <td class="text-end">Rp <?php echo number_format($total_obat, 0, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ringkasan</h5>
            </div>
            <div class="card-body">
                <strong>Pasien:</strong>
                <p><?php echo htmlspecialchars($appointment['nama_hewan']); ?></p>
                <strong>Pemilik:</strong>
                <p><?php echo htmlspecialchars($appointment['nama_pemilik']); ?></p>
                <strong>Dokter:</strong>
                <p><?php echo htmlspecialchars($appointment['nama_dokter']); ?></p>
                <hr>
                
                <h4>Total Biaya Layanan:</h4>
                <h3 class="text-success">Rp <?php echo number_format($total_layanan, 0, ',', '.'); ?></h3>
                
                <h4>Total Biaya Obat:</h4>
                <h3 class="text-success">Rp <?php echo number_format($total_obat, 0, ',', '.'); ?></h3>
                <hr>
                
                <h2 class="fw-bold">Total Tagihan:</h2>
                <h1 class="text-danger fw-bolder">Rp <?php echo number_format($total_layanan + $total_obat, 0, ',', '.'); ?></h1>
                
                <form action="/actions/admin/billing/process_payment.php" method="POST" class="mt-3">
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
                    <input type="hidden" name="total_tagihan" value="<?php echo $total_layanan + $total_obat; ?>">
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            Konfirmasi Pembayaran Lunas
                        </button>
                        <a href="/pages/admin/billing/index.php" class="btn btn-secondary">Kembali ke Antrian</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// 4. Panggil footer
include_once __DIR__ . '/../../layouts/admin_footer.php';
?>