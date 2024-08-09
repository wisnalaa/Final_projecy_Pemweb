<?php

$servername = "localhost";   // Ganti dengan nama server MySQL Anda
$username = "root";      // Ganti dengan username MySQL Anda
$password = "";      // Ganti dengan password MySQL Anda
$dbname = "wishlistdb";      // Ganti dengan nama database yang ingin Anda gunakan

// Buat koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Periksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo"Koneksi Berhasil";
}

?>
