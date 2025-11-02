<?php
// 1. Sertakan header publik
include_once __DIR__ . '/pages/layouts/public_header.php';
// 2. Sertakan koneksi database
include_once __DIR__ . '/config/database.php';

$pdo = getDBConnection();

// 3. Ambil data untuk ditampilkan (Contoh: 3 layanan)
$stmt_services = $pdo->query("SELECT nama_layanan, deskripsi 
                             FROM service 
                             WHERE status_tersedia = 1 
                             LIMIT 3");

// 4. Ambil data untuk ditampilkan (Contoh: 3 dokter aktif)
$stmt_doctors = $pdo->query("SELECT nama_dokter, spesialisasi, foto_url 
                            FROM veterinarian 
                            WHERE status = 'Aktif' 
                            LIMIT 3");
?>

<div class="hero-section">
    <div class="container">
        <h1>Selamat Datang di Klinik Hewan Kami</h1>
        <p class="lead">Memberikan perawatan terbaik untuk hewan kesayangan Anda.</p>
        <a href="/pages/auth/register.php" class="btn btn-primary btn-lg">Booking Sekarang</a>
    </div>
</div>

<div id="services" class="container mt-5">
    <h2 class="section-title">Layanan Kami</h2>
    <div class="row">
        <?php while($service = $stmt_services->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($service['nama_layanan']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars(substr($service['deskripsi'] ?? 'Layanan terbaik dari kami...', 0, 100)) . '...'; ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<div id="doctors" class="container mt-5">
    <h2 class="section-title">Tim Dokter Kami</h2>
    <div class="row">
        <?php while($doctor = $stmt_doctors->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <img src="<?php echo htmlspecialchars($doctor['foto_url'] ?? 'https://via.placeholder.com/150'); ?>" class="card-img-top" alt="Foto Dokter" style="width:150px; height:150px; object-fit:cover; margin: 15px auto 0; border-radius: 50%;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($doctor['nama_dokter']); ?></h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($doctor['spesialisasi']); ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
// 5. Sertakan footer publik
include_once __DIR__ . '/pages/layouts/public_footer.php';
?>