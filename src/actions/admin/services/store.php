<?php
// 1. Pastikan diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/services/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil data dari form
$nama_layanan = $_POST['nama_layanan'];
$kategori = $_POST['kategori'];
$harga = $_POST['harga'];
$durasi_estimasi = $_POST['durasi_estimasi'];
$status_tersedia = $_POST['status_tersedia'];
$deskripsi = $_POST['deskripsi'];

// 4. Validasi sederhana
if (empty($nama_layanan) || empty($kategori) || empty($harga)) {
    header('Location: /pages/admin/services/create.php?error=datakosong');
    exit;
}

// Handle data opsional (jika kosong, masukkan NULL)
$durasi_estimasi = empty($durasi_estimasi) ? null : $durasi_estimasi;
$deskripsi = empty($deskripsi) ? null : $deskripsi;

// 5. Coba simpan ke database
try {
    $pdo = getDBConnection();
    
    $sql = "INSERT INTO service 
                (nama_layanan, kategori, harga, durasi_estimasi, deskripsi, status_tersedia) 
            VALUES 
                (?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi dengan data
    $stmt->execute([
        $nama_layanan,
        $kategori,
        $harga,
        $durasi_estimasi,
        $deskripsi,
        $status_tersedia
    ]);

    // 6. Redirect kembali ke halaman daftar layanan
    header("Location: /pages/admin/services/index.php?status=sukses_tambah");
    exit;

} catch (PDOException $e) {
    // BENAR: Tag <pre> ada di dalam string
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>