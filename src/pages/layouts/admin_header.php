<?php
// ==================================
// == PASANG SATPAM DOKTER DI SINI ==
// ==================================
include_once __DIR__ . '/../../config/doctor_auth_check.php';

// Ambil path script saat ini
$current_page = $_SERVER['SCRIPT_NAME'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sedikit style tambahan */
        .sidebar {
            background-color: #f8f9fa;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            padding-top: 20px;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h5 class="px-3">Klinik Hewan</h5>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'doctors')) echo 'active'; ?>" 
               href="/pages/admin/doctors/index.php">
                Manajemen Dokter
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'services')) echo 'active'; ?>" 
               href="/pages/admin/services/index.php">
                Manajemen Layanan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'medicines')) echo 'active'; ?>" 
               href="/pages/admin/medicines/index.php">
                Manajemen Obat
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'owners')) echo 'active'; ?>" 
               href="/pages/admin/owners/index.php">
                Manajemen Pemilik
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'pets')) echo 'active'; ?>" 
               href="/pages/admin/pets/index.php">
                Manajemen Hewan
            </a>
        </li>
    </ul>

    <h6 class="px-3 mt-4 mb-1 text-muted">
        <span>Operasional</span>
    </h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'appointments')) echo 'active'; ?>" 
               href="/pages/admin/appointments/index.php">
                Manajemen Appointment
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(str_contains($current_page, 'billing')) echo 'active'; ?>" 
               href="/pages/admin/billing/index.php">
                Kasir / Tagihan
            </a>
        </li>
    </ul>
</div>
</div>

<div class="main-content">
    <div class="container-fluid">
        <main>


<?php
// Dapatkan path script saat ini
$current_page = $_SERVER['SCRIPT_NAME'];
?>