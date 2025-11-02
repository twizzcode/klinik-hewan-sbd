<?php
function getDBConnection() {
   $host = 'db';
   $dbName = 'klinik_hewan';
   $username = 'user';
  $password = 'password';
    
  $dsn = "mysql:host=$host;dbname=$dbName;charset=UTF8";
    
   try {
        $pdo = new PDO($dsn, $username, $password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;

    } catch (PDOException $e) {
        echo 'Koneksi gagal: ' . $e->getMessage();
        exit();
    }
}
?>
