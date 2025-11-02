<?php
// 1. Mulai Session dan panggil 'satpam'
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/doctor_auth_check.php';

// 2. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    exit;
}

// 3. Ambil data dari form
$rekam_id = $_POST['rekam_id'];
$obat_id = $_POST['obat_id'];
$jumlah = $_POST['jumlah'];
$dosis = $_POST['dosis'];

// 4. Validasi
if (empty($rekam_id) || empty($obat_id) || empty($jumlah) || empty($dosis)) {
    header('Location: /pages/doctor/prescription.php?rekam_id=' . $rekam_id . '&error=datakosong');
    exit;
}

$pdo = getDBConnection();
try {
    // Mulai Transaksi
    $pdo->beginTransaction();

    // ===========================================
    // Query 1: Ambil data obat (harga & stok)
    // ===========================================
    $sql_med = "SELECT harga_jual, stok FROM medicine WHERE obat_id = ?";
    $stmt_med = $pdo->prepare($sql_med);
    $stmt_med->execute([$obat_id]);
    $medicine = $stmt_med->fetch(PDO::FETCH_ASSOC);

    if (!$medicine) {
        throw new Exception("Obat tidak ditemukan.");
    }

    // Cek apakah stok cukup
    if ($medicine['stok'] < $jumlah) {
        throw new Exception("Stok obat tidak mencukupi (sisa " . $medicine['stok'] . ").");
    }

    // ===========================================
    // Query 2: Hitung subtotal dan INSERT ke 'resep'
    // ===========================================
    $harga_satuan = $medicine['harga_jual'];
    $subtotal = $harga_satuan * $jumlah;
    
    $sql_insert = "INSERT INTO resep 
                    (rekam_id, obat_id, dosis, frekuensi, durasi, jumlah, harga_satuan, subtotal) 
                   VALUES 
                    (?, ?, ?, 'N/A', 'N/A', ?, ?, ?)"; // Kita gabung 'frekuensi' & 'durasi' ke 'dosis'
    
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->execute([
        $rekam_id,
        $obat_id,
        $dosis,
        $jumlah,
        $harga_satuan,
        $subtotal
    ]);

    // ===========================================
    // Query 3: UPDATE (kurangi) stok obat
    // ===========================================
    $stok_baru = $medicine['stok'] - $jumlah;
    $sql_update = "UPDATE medicine SET stok = ? WHERE obat_id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$stok_baru, $obat_id]);
    
    // Jika semua berhasil
    $pdo->commit();

    // 7. Redirect kembali ke halaman resep (untuk menambah obat lain)
    header("Location: /pages/doctor/prescription.php?rekam_id=" . $rekam_id . "&status=sukses_tambah_obat");
    exit;

} catch (Exception $e) {
    // Jika ada yang gagal
    $pdo->rollBack();
    $error = urlencode($e->getMessage());
    header("Location: /pages/doctor/prescription.php?rekam_id=" . $rekam_id . "&error=" . $error);
    exit;
}
?>