<?php
// 1. Mulai Session
session_start();

// 2. Sertakan koneksi
include_once __DIR__ . '/../../config/database.php';

// 3. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/doctor/login.php');
    exit;
}

// 4. Ambil data
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: /pages/doctor/login.php?error=Email dan password wajib diisi.');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Cari dokter berdasarkan email
    $sql = "SELECT * FROM veterinarian WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    // ===============================================
    // PERIKSA BARIS INI DENGAN TELITI
    // ===============================================
    // Cek apakah $doctor ada (email ditemukan)
    // DAN
    // Cek apakah $password (yang Anda ketik: '12345')
    // cocok dengan $doctor['password'] (hash dari database)
    if ($doctor && password_verify($password, $doctor['password'])) {
    // ===============================================
        
        // LOGIN BERHASIL!
        $_SESSION['user_id'] = $doctor['dokter_id'];
        $_SESSION['user_name'] = $doctor['nama_dokter'];
        $_SESSION['user_role'] = 'doctor';
        
        header("Location: /pages/doctor/dashboard.php");
        exit;

    } else {
        // LOGIN GAGAL!
        header("Location: /pages/doctor/login.php?error=Email atau password salah.");
        exit;
    }

} catch (PDOException $e) {
    echo "Error: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>