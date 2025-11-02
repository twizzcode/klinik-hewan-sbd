<?php
// src/config/database.php

function getDBConnection() {
    // Ambil info koneksi dari Environment Variables
    // getenv() akan membaca variabel yang disuntikkan oleh Render
    $host = getenv('DB_HOST');
    $dbName = getenv('DB_NAME');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    // Jika variabel tidak ada (misal: saat dijalankan lokal via XAMPP)
    // kita bisa fallback ke setting lokal
    if (empty($host)) {
        $host = 'localhost'; // atau 'db' jika pakai docker lokal
        $dbName = 'klinik_hewan';
        $username = 'root';
        $password = '';
    }

    $dsn = "mysql:host=$host;dbname=$dbName;charset=UTF8";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Tampilkan error yang lebih jelas saat hosting
        die('Koneksi database gagal: ' . $e->getMessage());
    }
}
?>