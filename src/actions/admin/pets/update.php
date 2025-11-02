<?php
// 1. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/pets/index.php');
    exit;
}

// 2. Sertakan koneksi
include_once __DIR__ . '/../../../config/database.php';

// 3. Ambil semua data dari form, TERMASUK 'pet_id'
$pet_id = $_POST['pet_id'];
$owner_id = $_POST['owner_id'];
$nama_hewan = $_POST['nama_hewan'];
$jenis = $_POST['jenis'];
$ras = $_POST['ras'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$warna = $_POST['warna'];
$ciri_khusus = $_POST['ciri_khusus'];

// 4. Validasi
if (empty($pet_id) || empty($owner_id) || empty($nama_hewan) || empty($jenis) || empty($jenis_kelamin)) {
    header('Location: /pages/admin/pets/edit.php?id=' . $pet_id . '&error=datakosong');
    exit;
}

// 5. Handle data opsional
$ras = empty($ras) ? null : $ras;
$tanggal_lahir = empty($tanggal_lahir) ? null : $tanggal_lahir;
$warna = empty($warna) ? null : $warna;
$ciri_khusus = empty($ciri_khusus) ? null : $ciri_khusus;

// 6. Coba update ke database
try {
    $pdo = getDBConnection();
    
    $sql = "UPDATE pet SET 
                owner_id = ?, nama_hewan = ?, jenis = ?, ras = ?, 
                jenis_kelamin = ?, tanggal_lahir = ?, warna = ?, ciri_khusus = ?
            WHERE pet_id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    // Eksekusi (urutan harus sama dengan '?')
    $stmt->execute([
        $owner_id, $nama_hewan, $jenis, $ras,
        $jenis_kelamin, $tanggal_lahir, $warna, $ciri_khusus,
        $pet_id // 'pet_id' terakhir untuk WHERE
    ]);

    // 7. Redirect kembali ke halaman daftar
    header("Location: /pages/admin/pets/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    echo "Gagal mengupdate data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>