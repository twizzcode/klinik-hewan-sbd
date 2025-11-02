<?php
// src/config/doctor_auth_check.php

// Mulai session di setiap halaman yang dilindungi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Cek apakah user_id ada di session
if (!isset($_SESSION['user_id'])) {
    // Jika TIDAK ADA, tendang ke login dokter
    header("Location: /pages/doctor/login.php?error=Anda harus login dulu.");
    exit;
}

// 2. Cek apakah rolenya 'doctor'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
    // Jika yang login ternyata PELANGGAN, tendang juga
    header("Location: /pages/doctor/login.php?error=Akses ditolak. Area ini khusus dokter.");
    exit;
}

// Jika lolos, biarkan script lanjut
?>