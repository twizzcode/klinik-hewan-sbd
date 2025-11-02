<?php
// 1. Sertakan koneksi
include_once __DIR__ . '/../../config/database.php';

// 2. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /pages/auth/register.php');
    exit;
}

// 3. Ambil data
$nama_lengkap = $_POST['nama_lengkap'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$password = $_POST['password'];

// 4. Validasi
if (empty($nama_lengkap) || empty($no_telepon) || empty($email) || empty($password)) {
    // Nanti kita buat notifikasi error yang lebih baik
    header('Location: /pages/auth/register.php?error=datakosong');
    exit;
}

// 5. Enkripsi Password!
// JANGAN PERNAH simpan password sebagai teks biasa. Kita gunakan HASH.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 6. Simpan ke database
try {
    $pdo = getDBConnection();
    
    // Perhatikan: query INSERT sekarang menyertakan 'password'
    $sql = "INSERT INTO owner (nama_lengkap, no_telepon, email, password) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nama_lengkap,
        $no_telepon,
        $email,
        $hashed_password // Simpan password yang sudah di-hash
    ]);

    // 7. Jika sukses, arahkan ke halaman login
    header("Location: /pages/auth/login.php?status=sukses_daftar");
    exit;

} catch (PDOException $e) {
    // Tangani error jika email atau no_telepon duplikat
    if ($e->errorInfo[1] == 1062) { // 1062 = Duplicate entry
        $errorMessage = "Email atau No. Telepon sudah terdaftar.";
        header("Location: /pages/auth/register.php?error=" . urlencode($errorMessage));
        exit;
    } else {
        echo "Gagal mendaftar: <pre>";
        print_r($e->getMessage());
        echo "</pre>";
    }
}
?>