<?php
// 1. Pastikan diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/pets/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil semua data dari form
$owner_id = $_POST['owner_id'];
$nama_hewan = $_POST['nama_hewan'];
$jenis = $_POST['jenis'];
$ras = $_POST['ras'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$warna = $_POST['warna'];
$ciri_khusus = $_POST['ciri_khusus'];
// 'status' dan 'tanggal_registrasi' akan diisi oleh default database

// 4. Validasi data wajib
if (empty($owner_id) || empty($nama_hewan) || empty($jenis) || empty($jenis_kelamin)) {
    header('Location: /pages/admin/pets/create.php?error=datakosong');
    exit;
}

// 5. Handle data opsional (jika kosong, masukkan NULL)
$ras = empty($ras) ? null : $ras;
$tanggal_lahir = empty($tanggal_lahir) ? null : $tanggal_lahir;
$warna = empty($warna) ? null : $warna;
$ciri_khusus = empty($ciri_khusus) ? null : $ciri_khusus;
// Kita tidak perlu handle berat_badan, foto_url, dll. karena mereka NULLable

// 6. Coba simpan ke database
try {
    $pdo = getDBConnection();
    
    $sql = "INSERT INTO pet 
                (owner_id, nama_hewan, jenis, ras, jenis_kelamin, tanggal_lahir, warna, ciri_khusus) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi dengan data (urutan harus sama dengan '?')
    $stmt->execute([
        $owner_id,
        $nama_hewan,
        $jenis,
        $ras,
        $jenis_kelamin,
        $tanggal_lahir,
        $warna,
        $ciri_khusus
    ]);

    // 7. Redirect kembali ke halaman daftar hewan
    header("Location: /pages/admin/pets/index.php?status=sukses_tambah");
    exit;

} catch (PDOException $e) {
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>