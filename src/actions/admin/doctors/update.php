<?php
// Pastikan file ini diakses melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metode tidak diizinkan!";
    header('Location: /pages/admin/doctors/index.php');
    exit;
}

include_once __DIR__ . '/../../../config/database.php';

// Ambil data dari form, TERMASUK 'dokter_id' yang tersembunyi
$dokter_id = $_POST['dokter_id'];
$nama_dokter = $_POST['nama_dokter'];
$no_lisensi = $_POST['no_lisensi'];
$spesialisasi = $_POST['spesialisasi'];
$no_telepon = $_POST['no_telepon'];
$email = $_POST['email'];
$tanggal_bergabung = $_POST['tanggal_bergabung'];

// Validasi sederhana
if (empty($dokter_id) || empty($nama_dokter) || empty($no_telepon) || empty($tanggal_bergabung)) {
    header('Location: /pages/admin/doctors/edit.php?id=' . $dokter_id . '&error=datakosong');
    exit;
}

try {
    $pdo = getDBConnection();
    
    // 2. Siapkan query SQL UPDATE
    $sql = "UPDATE veterinarian SET 
                nama_dokter = ?, 
                no_lisensi = ?, 
                spesialisasi = ?, 
                no_telepon = ?, 
                email = ?, 
                tanggal_bergabung = ? 
            WHERE dokter_id = ?"; // Kondisi WHERE sangat penting!
    
    $stmt = $pdo->prepare($sql);
    
    // 3. Eksekusi query dengan data
    $stmt->execute([
        $nama_dokter,
        $no_lisensi,
        $spesialisasi,
        $no_telepon,
        $email,
        $tanggal_bergabung,
        $dokter_id // 'dokter_id' masuk di akhir untuk WHERE
    ]);

    // 4. Redirect kembali ke halaman daftar dokter
    header("Location: /pages/admin/doctors/index.php?status=sukses_update");
    exit;

} catch (PDOException $e) {
    echo "Gagal mengupdate data: " . $e->getMessage();
}
?>