<?php
header("Access-Control-Allow-Origin: *"); // Mengizinkan semua origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Mengizinkan metode HTTP tertentu
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Header yang diizinkan
header("Access-Control-Allow-Credentials: true"); // Jika Anda memerlukan kredensial (opsional)

$host = "localhost";
$user = "root";
$pass = "";
$db = "showroom";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
