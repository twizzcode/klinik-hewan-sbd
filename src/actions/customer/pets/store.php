<?php
// 1. Mulai Session untuk ambil ID user
session_start();

// 2. Sertakan koneksi dan 'satpam'
// Kita panggil auth_check untuk memastikan user masih login saat mengirim form
include_once __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/auth_check.php'; // Keamanan tambahan

// 3. Pastikan diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    exit;
}

// 4. Ambil ID Pemilik dari SESSION (INI KUNCINYA)
$owner_id = $_SESSION['user_id'];

// 5. Ambil data dari form
$nama_hewan = $_POST['nama_hewan'];
$jenis = $_POST['jenis'];
$ras = $_POST['ras'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$warna = $_POST['warna'];
$ciri_khusus = $_POST['ciri_khusus'];

// 6. Validasi data wajib
if (empty($nama_hewan) || empty($jenis) || empty($jenis_kelamin)) {
    // Arahkan kembali ke form (seharusnya ada pesan error)
    header('Location: /pages/customer/pets/create.php?error=datakosong');
    exit;
}

// 7. Handle data opsional
$ras = empty($ras) ? null : $ras;
$tanggal_lahir = empty($tanggal_lahir) ? null : $tanggal_lahir;
$warna = empty($warna) ? null : $warna;
$ciri_khusus = empty($ciri_khusus) ? null : $ciri_khusus;

// 8. Coba simpan ke database
try {
    $pdo = getDBConnection();
    
    $sql = "INSERT INTO pet 
                (owner_id, nama_hewan, jenis, ras, jenis_kelamin, tanggal_lahir, warna, ciri_khusus) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi dengan data
    $stmt->execute([
        $owner_id, // Masukkan ID dari SESSION
        $nama_hewan,
        $jenis,
        $ras,
        $jenis_kelamin,
        $tanggal_lahir,
        $warna,
        $ciri_khusus
    ]);

    // 9. Redirect kembali ke halaman daftar hewan
    header("Location: /pages/customer/pets/index.php?status=sukses_tambah");
    exit;

} catch (PDOException $e) {
    echo "Gagal menyimpan data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>