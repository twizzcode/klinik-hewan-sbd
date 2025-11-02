<?php
// Pastikan file ini diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    // Redirect ke halaman index
    header('Location: /pages/admin/doctors/index.php');
    exit;
}

// Sertakan file koneksi
include_once __DIR__ . '/../../../config/database.php';

// Ambil data dari form
$nama_dokter = $_POST['nama_dokter'];
$no_lisensi = $_POST['no_lisensi'];
$spesialisasi = $_POST['spesialisasi'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$tanggal_bergabung = $_POST['tanggal_bergabung'];
// Set default untuk field yang tidak ada di form
$status = 'Aktif'; 
$jadwal_praktek = ''; // Bisa ditambahkan di form edit nanti

// Validasi sederhana (wajib diisi)
if (empty($nama_dokter) || empty($no_telepon) || empty($tanggal_bergabung)) {
    // Seharusnya ada notifikasi error, tapi untuk sekarang kita redirect
    header('Location: /pages/admin/doctors/create.php?error=datakosong');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Gunakan Prepared Statements untuk keamanan (mencegah SQL Injection)
    $sql = "INSERT INTO veterinarian 
                (nama_dokter, no_lisensi, spesialisasi, no_telepon, email, jadwal_praktek, status, tanggal_bergabung) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi query dengan data
    $stmt->execute([
        $nama_dokter,
        $no_lisensi,
        $spesialisasi,
        $no_telepon,
        $email,
        $jadwal_praktek,
        $status,
        $tanggal_bergabung
    ]);

    // Jika berhasil, redirect kembali ke halaman daftar dokter
    header("Location: /pages/admin/doctors/index.php?status=sukses");
    exit;

} catch (PDOException $e) {
    // Jika gagal, tampilkan pesan error
    // Di aplikasi nyata, ini harus dicatat (log)
    echo "Gagal menyimpan data: " . $e->getMessage();
}

?>