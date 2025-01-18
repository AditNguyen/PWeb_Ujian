<?php
header("Access-Control-Allow-Origin: *"); // Mengizinkan semua origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Mengizinkan metode HTTP tertentu
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Header yang diizinkan
header("Access-Control-Allow-Credentials: true"); // Jika Anda memerlukan kredensial (opsional)

header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$sql = $conn->prepare("DELETE FROM cars WHERE id = ?");
$sql->bind_param("i", $id);

if ($sql->execute()) {
    echo json_encode(["message" => "VGA deleted successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Error deleting VGA"]);
}
?>
