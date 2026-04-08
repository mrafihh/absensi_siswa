<?php
$host = "localhost";
$user = "rafi";       // Username default XAMPP
$pass = "rafi";           // Password default XAMPP kosong
$db   = "db_absensi";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
