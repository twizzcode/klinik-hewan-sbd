<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/layouts/header.php';
include_once __DIR__ . '/../../config/database.php';

// 2. Ambil ID Rekam Medis dari URL
if (!isset($_GET['rekam_id'])) {
    echo "<div class='alert alert-danger'>ID Rekam Medis tidak ditemukan.</div>";
    include_once __DIR__ . '/layouts/footer.php';
    exit;
}
$rekam_id = $_GET['rekam_id'];

$pdo = getDBConnection();

// ==========================================================
// 1. AMBIL DATA OBAT UNTUK DROPDOWN
// ==========================================================
// Ambil obat yang statusnya 'Tersedia' dan stoknya ada
$sql_meds = "SELECT obat_id, nama_obat, satuan, harga_jual, stok 
             FROM medicine 
             WHERE status_tersedia = 1 AND stok > 0 
             ORDER BY nama_obat ASC";
$stmt_meds = $pdo->query($sql_meds);

// ==========================================================
// 2. AMBIL OBAT YANG SUDAH ADA DI RESEP INI
// ==========================================================
$sql_resep = "SELECT r.resep_id, m.nama_obat, r.dosis, r.jumlah, r.subtotal
              FROM resep r
              JOIN medicine m ON r.obat_id = m.obat_id
              WHERE r.rekam_id = ?";
$stmt_resep = $pdo->prepare($sql_resep);
$stmt_resep->execute([$rekam_id]);
?>

<div class="row g-4">
    <div class="col-md-5">
        <h3>Resep Obat</h3>
        <div class="card">
            <div class="card-body">
                <form action="/actions/doctor/store_prescription.php" method="POST">
                    <input type="hidden" name="rekam_id" value="<?php echo $rekam_id; ?>">
                    
                    <div class="mb-3">
                        <label for="obat_id" class="form-label">Pilih Obat</label>
                        <select class="form-select" id="obat_id" name="obat_id" required>
                            <option value="" selected disabled>-- Cari Obat --</option>
                            <?php
                            while ($med = $stmt_meds->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($med['obat_id']) . "' data-harga='" . $med['harga_jual'] . "'>" 
                                   . htmlspecialchars($med['nama_obat']) 
                                   . " (Stok: " . $med['stok'] . ")"
                                   . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="dosis" class="form-label">Dosis / Aturan Pakai</label>
                        <input type="text" class="form-control" id="dosis" name="dosis" placeholder="Contoh: 3x1 hari sesudah makan" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">
                        + Tambahkan ke Resep
                    </button>
                </form>
            </div>
        </div>
        
        <a href="/pages/doctor/dashboard.php" class="btn btn-primary mt-3 w-100">
            Selesai & Kembali ke Dashboard
        </a>
    </div>

    <div class="col-md-7">
        <h3>Daftar Obat (Resep ID: <?php echo $rekam_id; ?>)</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Obat</th>
                        <th>Dosis</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_harga = 0;
                    if ($stmt_resep->rowCount() > 0) {
                        while ($item = $stmt_resep->fetch(PDO::FETCH_ASSOC)) {
                            $total_harga += $item['subtotal'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($item['nama_obat']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['dosis']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['jumlah']) . "</td>";
                            echo "<td>Rp " . number_format($item['subtotal'], 0, ',', '.') . "</td>";
                            echo "<td><a href='#' class='btn btn-danger btn-sm'>Hapus</a></td>"; // (Fitur hapus bisa ditambahkan nanti)
                            echo "</tr>";
                        }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Belum ada obat di resep ini.</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="3" class="text-end">Total Harga Obat:</td>
                        <td colspan="2">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php
// 4. Panggil footer
include_once __DIR__ . '/layouts/footer.php';
?>