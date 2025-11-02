<?php
// 1. Mulai Session
// Ini PENTING! Harus ada di baris paling atas
session_start();

// 2. Sertakan koneksi
include_once __DIR__ . '/../../config/database.php';

// 3. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/auth/login.php');
    exit;
}

// 4. Ambil data
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: /pages/auth/login.php?error=datakosong');
    exit;
}

// 5. Cek data ke database
try {
    $pdo = getDBConnection();
    
    // Cari owner berdasarkan email
    $sql = "SELECT * FROM owner WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    
    $owner = $stmt->fetch(PDO::FETCH_ASSOC);

    // 6. Verifikasi
    // Cek apakah owner-nya ada DAN password-nya cocok
    if ($owner && password_verify($password, $owner['password'])) {
        
        // LOGIN BERHASIL!
        // Simpan data owner ke session
        $_SESSION['user_id'] = $owner['owner_id'];
        $_SESSION['user_name'] = $owner['nama_lengkap'];
        $_SESSION['user_role'] = 'owner'; // Kita beri role
        
        // Arahkan ke dashboard pelanggan (halaman ini belum kita buat)
        header("Location: /pages/customer/dashboard.php");
        exit;

    } else {
        // LOGIN GAGAL!
        header("Location: /pages/auth/login.php?error=Email atau password salah.");
        exit;
    }

} catch (PDOException $e) {
    echo "Error: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>