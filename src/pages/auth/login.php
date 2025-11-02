<?php
// Kita akan pakai layout publik
include_once __DIR__ . '/../layouts/public_header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Login Pelanggan</h3>
                </div>
                <div class="card-body">
                    
                    <?php
                    // Tampilkan notifikasi jika ada
                    if (isset($_GET['status']) && $_GET['status'] == 'sukses_daftar') {
                        echo "<div class='alert alert-success'>Pendaftaran berhasil! Silakan login.</div>";
                    }
                    if (isset($_GET['error'])) {
                        echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</div>";
                    }
                    ?>
                    
                    <form action="/actions/auth/do_login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <p class="text-center mt-3">
                            Belum punya akun? <a href="/pages/auth/register.php">Daftar di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Kita pakai layout publik
include_once __DIR__ . '/../layouts/public_footer.php';
?>