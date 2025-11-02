<?php
// 1. Pastikan diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/owners/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil data dari form
$nama_lengkap = $_POST['nama_lengkap'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$catatan = $_POST['catatan'];
// tanggal_registrasi akan diisi otomatis oleh database (DEFAULT CURRENT_TIMESTAMP)

// 4. Validasi data wajib
if (empty($nama_lengkap) || empty($no_telepon)) {
    header('Location: /pages/admin/owners/create.php?error=datakosong');
    exit;
}

// 5. Handle data opsional (jika kosong, masukkan NULL)
$email = empty($email) ? null : $email;
$alamat = empty($alamat) ? null : $alamat;
$catatan = empty($catatan) ? null : $catatan;

// 6. Coba simpan ke database
try {
    $pdo = getDBConnection();
    
    $sql = "INSERT INTO owner 
                (nama_lengkap, alamat, no_telepon, email, catatan) 
            VALUES 
                (?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi dengan data (urutan harus sama dengan '?')
    $stmt->execute([
        $nama_lengkap,
        $alamat,
        $no_telepon,
        $email,
        $catatan
    ]);

    // 7. Redirect kembali ke halaman daftar pemilik
    header("Location: /pages/admin/owners/index.php?status=sukses_tambah");
    exit;

} catch (PDOException $e) {
    // Tangani error jika email atau no_telepon duplikat (jika Anda set UNIQUE)
    if ($e->errorInfo[1] == 1062) { // Error code 1062 = Duplicate entry
        $errorMessage = "Gagal menyimpan: Email atau No. Telepon mungkin sudah terdaftar.";
        header("Location: /pages/admin/owners/create.php?error=" . urlencode($errorMessage));
        exit;
    } else {
        echo "Gagal menyimpan data: <pre>";
        print_r($e->getMessage());
        echo "</pre>";
    }
}
?>