<?php
// 1. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/owners/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil data dari form, TERMASUK 'owner_id'
$owner_id = $_POST['owner_id'];
$nama_lengkap = $_POST['nama_lengkap'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$alamat = $_POST['alamat'];
$catatan = $_POST['catatan'];

// 4. Validasi
if (empty($owner_id) || empty($nama_lengkap) || empty($no_telepon)) {
    header('Location: /pages/admin/owners/edit.php?id=' . $owner_id . '&error=datakosong');
    exit;
}

// 5. Handle data opsional
$email = empty($email) ? null : $email;
$alamat = empty($alamat) ? null : $alamat;
$catatan = empty($catatan) ? null : $catatan;

// 6. Coba update ke database
try {
    $pdo = getDBConnection();
    
    $sql = "UPDATE owner SET 
                nama_lengkap = ?, 
                alamat = ?, 
                no_telepon = ?, 
                email = ?, 
                catatan = ?
            WHERE owner_id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi (urutan harus sama dengan '?')
    $stmt->execute([
        $nama_lengkap,
        $alamat,
        $no_telepon,
        $email,
        $catatan,
        $owner_id // 'owner_id' terakhir untuk WHERE
    ]);

    // 7. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/owners/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $errorMessage = "Gagal mengupdate: Email atau No. Telepon mungkin sudah terdaftar.";
        header("Location: /pages/admin/owners/edit.php?id=" . $owner_id . "&error=" . urlencode($errorMessage));
        exit;
    } else {
        echo "Gagal mengupdate data: <pre>";
        print_r($e->getMessage());
        echo "</pre>";
    }
}
?>