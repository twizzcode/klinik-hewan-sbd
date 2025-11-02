<?php
// 1. Pastikan diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/medicines/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil semua data dari form
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
if (empty($nama_obat) || empty($kategori) || empty($satuan) || empty($harga_beli) || empty($harga_jual)) {
    header('Location: /pages/admin/medicines/create.php?error=datakosong');
    exit;
}

// 5. Handle data opsional (jika kosong, masukkan NULL)
$bentuk_sediaan = empty($bentuk_sediaan) ? null : $bentuk_sediaan;
$expired_date = empty($expired_date) ? null : $expired_date;
$supplier = empty($supplier) ? null : $supplier;
$deskripsi = empty($deskripsi) ? null : $deskripsi;
$stok = empty($stok) ? 0 : $stok; // Default stok ke 0 jika kosong

// 6. Coba simpan ke database
try {
    $pdo = getDBConnection();
    
    $sql = "INSERT INTO medicine 
                (nama_obat, kategori, bentuk_sediaan, satuan, stok, harga_beli, harga_jual, expired_date, supplier, deskripsi, status_tersedia) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi dengan data (pastikan urutannya SAMA dengan '?' di atas)
    $stmt->execute([
        $nama_obat,
        $kategori,
        $bentuk_sediaan,
        $satuan,
        $stok,
        $harga_beli,
        $harga_jual,
        $expired_date,
        $supplier,
        $deskripsi,
        $status_tersedia
    ]);

    // 7. Redirect kembali ke halaman daftar obat
    header("Location: /pages/admin/medicines/index.php?status=sukses_tambah");
    exit;

} catch (PDOException $e) {
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>