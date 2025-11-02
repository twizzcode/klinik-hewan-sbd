<?php
// 1. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/services/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil data dari form, TERMASUK 'layanan_id'
$layanan_id = $_POST['layanan_id'];
$nama_layanan = $_POST['nama_layanan'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$durasi_estimasi = $_POST['durasi_estimasi'];
$status_tersedia = $_POST['status_tersedia'];
$deskripsi = $_POST['deskripsi'];

// 4. Validasi
if (empty($layanan_id) || empty($nama_layanan) || empty($kategori) || empty($harga)) {
    header('Location: /pages/admin/services/edit.php?id=' . $layanan_id . '&error=datakosong');
    exit;
}

// Handle data opsional
$durasi_estimasi = empty($durasi_estimasi) ? null : $durasi_estimasi;
$deskripsi = empty($deskripsi) ? null : $deskripsi;

// 5. Coba update ke database
try {
    $pdo = getDBConnection();
    
    $sql = "UPDATE service SET 
                nama_layanan = ?, 
                kategori = ?, 
                harga = ?, 
                durasi_estimasi = ?, 
                deskripsi = ?, 
                status_tersedia = ? 
            WHERE layanan_id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi (urutan harus sama dengan tanda '?' di atas)
    $stmt->execute([
        $nama_layanan,
        $kategori,
        $harga,
        $durasi_estimasi,
        $deskripsi,
        $status_tersedia,
        $layanan_id // 'layanan_id' terakhir untuk WHERE
    ]);

    // 6. Redirect kembali ke halaman daftar layanan
    header("Location: /pages/admin/services/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    echo "Gagal mengupdate data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>