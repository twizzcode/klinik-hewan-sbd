<?php
// 1. MULAI SESSION
// Pastikan session_start() dipanggil sebelum output apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. PANGGIL SATPAM
include_once __DIR__ . '/../../../config/auth_check.php';

// 3. Logika untuk menu 'active'
$current_page = $_SERVER['SCRIPT_NAME'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/pages/customer/dashboard.php"><b>Portal Pelanggan</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if(str_contains($current_page, 'dashboard.php')) echo 'active'; ?>" 
                       href="/pages/customer/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(str_contains($current_page, 'pets')) echo 'active'; ?>" 
                       href="/pages/customer/pets/index.php">Hewan Saya</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php if(str_contains($current_page, 'booking')) echo 'active'; ?>" 
                       href="/pages/customer/booking/index.php">Buat Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(str_contains($current_page, 'my_appointments')) echo 'active'; ?>" 
                       href="/pages/customer/my_appointments/index.php">Janji Temu Saya</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/actions/auth/do_logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-4">