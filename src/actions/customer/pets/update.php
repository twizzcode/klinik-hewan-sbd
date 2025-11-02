<?php
// 1. Mulai Session dan panggil 'satpam'
session_start();
include_once __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/auth_check.php';

// 2. Pastikan diakses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    exit;
}

// 3. Ambil data
$pet_id = $_POST['pet_id'];
$owner_id = $_SESSION['user_id']; // Ambil ID pemilik dari session
$nama_hewan = $_POST['nama_hewan'];
$jenis = $_POST['jenis'];
$ras = $_POST['ras'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$warna = $_POST['warna'];
$ciri_khusus = $_POST['ciri_khusus'];
$status = $_POST['status']; // Ambil status

// 4. Validasi
if (empty($pet_id) || empty($nama_hewan) || empty($jenis) || empty($jenis_kelamin) || empty($status)) {
    header('Location: /pages/customer/pets/edit.php?id=' . $pet_id . '&error=datakosong');
    exit;
}

// 5. Handle data opsional
$ras = empty($ras) ? null : $ras;
$tanggal_lahir = empty($tanggal_lahir) ? null : $tanggal_lahir;
$warna = empty($warna) ? null : $warna;
$ciri_khusus = empty($ciri_khusus) ? null : $ciri_khusus;

try {
    $pdo = getDBConnection();
    
    // 6. KEAMANAN: Cek lagi di sini sebelum UPDATE
    $sql_check = "SELECT pet_id FROM pet WHERE pet_id = ? AND owner_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$pet_id, $owner_id]);
    
    if ($stmt_check->rowCount() == 0) {
        // Jika tidak ada data, berarti user mencoba meng-edit data orang lain
        header("Location: /pages/customer/pets/index.php?error=aksesditolak");
        exit;
    }

    // 7. Jika aman, lakukan UPDATE
    $sql_update = "UPDATE pet SET 
                    nama_hewan = ?, jenis = ?, ras = ?, 
                    jenis_kelamin = ?, tanggal_lahir = ?, warna = ?, 
                    ciri_khusus = ?, status = ?
                   WHERE pet_id = ? AND owner_id = ?"; // Dobel cek owner_id
    
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        $nama_hewan, $jenis, $ras,
        $jenis_kelamin, $tanggal_lahir, $warna,
        $ciri_khusus, $status,
        $pet_id, $owner_id
    ]);

    // 8. Redirect kembali
    header("Location: /pages/customer/pets/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    echo "Gagal mengupdate data: <pre>";
    print_r($e->getMessage());
    echo "</pre>";
}
?>