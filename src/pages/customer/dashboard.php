<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/layouts/header.php';
?>

<?php
if (isset($_GET['status']) && $_GET['status'] == 'sukses_booking') {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Booking Berhasil!</strong> Janji temu Anda telah diajukan dan menunggu konfirmasi dari admin.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
?>
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p class="col-md-8 fs-4">Ini adalah halaman dashboard Anda. Dari sini Anda bisa mengelola data hewan peliharaan Anda dan membuat janji temu (appointment).</p>
        
        <a href="/pages/customer/pets/index.php" class="btn btn-primary btn-lg">Lihat Hewan Saya</a>
        <a href="/pages/customer/booking/index.php" class="btn btn-success btn-lg">Booking Sekarang</a>
    </div>
</div>

<h2>Janji Temu Anda Berikutnya</h2>
<p>Anda belum memiliki janji temu yang akan datang.</p>


<?php
// 2. Panggil footer
include_once __DIR__ . '/layouts/footer.php';
?>