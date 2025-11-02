<?php
// src/config/auth_check.php

// Mulai session di setiap halaman yang dilindungi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah 'user_id' ada di session
if (!isset($_SESSION['user_id'])) {
    // Jika TIDAK ADA, tendang dia kembali ke halaman login
    header("Location: /pages/auth/login.php?error=Anda harus login untuk mengakses halaman ini.");
    exit;
}

// Opsional: Cek juga apakah rolenya 'owner'
if ($_SESSION['user_role'] !== 'owner') {
    // Jika yang login ternyata admin, tendang juga (nanti)
    header("Location: /pages/auth/login.php?error=Akses ditolak.");
    exit;
}

// Jika lolos, biarkan script lanjut
?>