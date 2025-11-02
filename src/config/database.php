<?php
// src/config/database.php

/**
 * Fungsi ini untuk membuat dan mengembalikan koneksi PDO ke database.
 * Kita akan memanggil fungsi ini di setiap halaman yang butuh data.
 */
function getDBConnection() {
    // Pengaturan ini HARUS SAMA dengan yang ada di docker-compose.yml
    $host = 'db';           // Ini adalah nama 'service' database di Docker
    $dbName = 'klinik_hewan'; // Nama database Anda
    $username = 'user';       // User dari docker-compose.yml
    $password = 'password';   // Password dari docker-compose.yml
    
    // DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$dbName;charset=UTF8";
    
    try {
        // Buat objek PDO (PHP Data Objects)
        $pdo = new PDO($dsn, $username, $password);
        
        // Atur agar PDO memberitahu kita jika ada error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Kembalikan objek koneksi
        return $pdo;

    } catch (PDOException $e) {
        // Jika koneksi gagal, tampilkan pesan error dan hentikan script
        echo 'Koneksi gagal: ' . $e->getMessage();
        exit();
    }
}
?>