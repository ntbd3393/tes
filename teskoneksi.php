<?php
$host = 'localhost';
$db = 'my_saas_db';
$user = 'root';
$pass = '';
$port = 3307;
$conn = new mysqli($host, $user, $pass, $db, 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Koneksi berhasil!";
}
?>