<?php
// 1. PANGGIL SATPAM DOKTER
include_once __DIR__ . '/../../../config/doctor_auth_check.php';
// 2. (BARU) Logika untuk menu 'active'
$current_page = $_SERVER['SCRIPT_NAME'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/pages/doctor/dashboard.php"><b>Portal Dokter</b></a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#doctorNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="doctorNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php if(str_contains($current_page, 'doctor/dashboard.php')) echo 'active'; ?>" 
                       href="/pages/doctor/dashboard.php">
                       Jadwal Saya
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <span class="navbar-text text-white">
                        Dr. <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </span>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if(str_contains($current_page, 'admin/')) echo 'active'; ?>" 
                       href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Portal Admin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="/pages/admin/appointments/index.php">Manajemen Appointment</a></li>
                        <li><a class="dropdown-item" href="/pages/admin/billing/index.php">Kasir / Tagihan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/pages/admin/doctors/index.php">Data Dokter</a></li>
                        <li><a class="dropdown-item" href="/pages/admin/services/index.php">Data Layanan</a></li>
                        <li><a class="dropdown-item" href="/pages/admin/medicines/index.php">Data Obat</a></li>
                        <li><a class="dropdown-item" href="/pages/admin/owners/index.php">Data Pemilik</a></li>
                        <li><a class="dropdown-item" href="/pages/admin/pets/index.php">Data Hewan</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="/actions/auth/do_logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-4">