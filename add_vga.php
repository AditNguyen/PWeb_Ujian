<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Tangani preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require "db.php"; // Pastikan file db.php ada dan berfungsi

// Dekode data JSON yang diterima
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data input
if (
    !$data ||
    !isset($data['nama_vga']) ||
    !isset($data['merek']) ||
    !isset($data['tanggal_rilis']) ||
    !isset($data['harga']) ||
    !isset($data['foto_url'])
) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input data"]);
    exit;
}

// Ambil data dari request
$nama_mobil = $data['nama_vga'];
$merek = $data['merek'];
$tanggal_rilis = $data['tanggal_rilis'];
$harga = intval(str_replace('.', '', $data['harga'])); // Konversi harga ke angka
$foto_url = $data['foto_url'];

// Query untuk menambahkan data ke database
$sql = $conn->prepare("INSERT INTO cars (nama_vga, merek, tanggal_rilis, harga, foto_url) VALUES (?, ?, ?, ?, ?)");
if (!$sql) {
    http_response_code(500);
    echo json_encode(["message" => "Database query preparation failed"]);
    exit;
}
$sql->bind_param("sssds", $nama_vga, $merek, $tanggal_rilis, $harga, $foto_url);

if ($sql->execute()) {
    echo json_encode(["message" => "VGA added successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Error adding VGA"]);
}

$sql->close();
$conn->close();
?>
