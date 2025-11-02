<?php
// 1. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/medicines/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil semua data dari form, TERMASUK 'obat_id'
$obat_id = $_POST['obat_id'];
$nama_obat = $_POST['nama_obat'];
$kategori = $_POST['kategori'];
$bentuk_sediaan = $_POST['bentuk_sediaan'];
$satuan = $_POST['satuan'];
$stok = $_POST['stok'];
$harga_beli = $_POST['harga_beli'];
$harga_jual = $_POST['harga_jual'];
$expired_date = $_POST['expired_date'];
$supplier = $_POST['supplier'];
$deskripsi = $_POST['deskripsi'];
$status_tersedia = $_POST['status_tersedia'];

// 4. Validasi data wajib
if (empty($obat_id) || empty($nama_obat) || empty($kategori) || empty($satuan) || empty($harga_beli) || empty($harga_jual)) {
    header('Location: /pages/admin/medicines/edit.php?id=' . $obat_id . '&error=datakosong');
    exit;
}

// 5. Handle data opsional
$bentuk_sediaan = empty($bentuk_sediaan) ? null : $bentuk_sediaan;
$expired_date = empty($expired_date) ? null : $expired_date;
$supplier = empty($supplier) ? null : $supplier;
$deskripsi = empty($deskripsi) ? null : $deskripsi;
$stok = empty($stok) ? 0 : $stok;

// 6. Coba update ke database
try {
    $pdo = getDBConnection();
    
    $sql = "UPDATE medicine SET 
                nama_obat = ?, kategori = ?, bentuk_sediaan = ?, 
                satuan = ?, stok = ?, harga_beli = ?, harga_jual = ?, 
                expired_date = ?, supplier = ?, deskripsi = ?, 
                status_tersedia = ? 
            WHERE obat_id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi (urutan harus sama dengan '?' di atas)
    $stmt->execute([
        $nama_obat, $kategori, $bentuk_sediaan,
        $satuan, $stok, $harga_beli, $harga_jual,
        $expired_date, $supplier, $deskripsi,
        $status_tersedia,
        $obat_id // 'obat_id' terakhir untuk WHERE
    ]);

    // 7. Redirect kembali ke halaman daftar obat
    header("Location: /pages/admin/medicines/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    echo "Gagal mengupdate data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>