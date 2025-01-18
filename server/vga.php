<?php
require "db.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        // Ambil semua data mobil
        $sql = "SELECT * FROM cars";
        $result = $conn->query($sql);
        $cars = [];
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }
        echo json_encode($cars);
        break;

    case "POST":
        // Tambah mobil baru
        $nama_vga = $data['nama_vga'];
        $merek = $data['merek'];
        $tanggal_rilis = $data['tanggal_rilis'];
        $harga = $data['harga'];
        $foto_url = isset($data['foto_url']) ? $data['foto_url'] : null; // Memeriksa jika ada foto_url

        $sql = $conn->prepare("INSERT INTO cars (nama_vga, merek, tanggal_rilis, harga, foto_url) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssis", $nama_vga, $merek, $tanggal_rilis, $harga, $foto_url);
        $sql->execute();
        echo json_encode(["message" => "Mobil berhasil ditambahkan"]);
        break;

    case "PUT":
        // Update data mobil
        $id = $data['id'];
        $nama_vga = $data['nama_vga'];
        $merek = $data['merek'];
        $tanggal_rilis = $data['tanggal_rilis'];
        $harga = $data['harga'];
        $foto_url = isset($data['foto_url']) ? $data['foto_url'] : null;

        $sql = $conn->prepare("UPDATE cars SET nama_vga = ?, merek = ?, tanggal_rilis = ?, harga = ?, foto_url = ? WHERE id = ?");
        $sql->bind_param("sssisi", $nama_vga, $merek, $tanggal_rilis, $harga, $foto_url, $id);
        $sql->execute();
        echo json_encode(["message" => "Mobil berhasil diperbarui"]);
        break;

    case "DELETE":
        // Hapus mobil berdasarkan ID
        $id = $data['id'];
        $sql = $conn->prepare("DELETE FROM cars WHERE id = ?");
        $sql->bind_param("i", $id);
        $sql->execute();
        echo json_encode(["message" => "Mobil berhasil dihapus"]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>
