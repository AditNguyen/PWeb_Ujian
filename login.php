<?php
// Mengatur header untuk mengizinkan CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json"); // Pastikan semua respon berupa JSON

// Menangani preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require "db.php"; // Mengimpor koneksi database

// Mendekode data JSON yang diterima
$data = json_decode(file_get_contents("php://input"), true);

// Validasi data input
if (!$data || !isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input data"]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

// Query untuk mencari user berdasarkan username
$sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
if (!$sql) {
    http_response_code(500);
    echo json_encode(["message" => "Database query preparation failed"]);
    exit;
}
$sql->bind_param("s", $username);
$sql->execute();
$result = $sql->get_result();

// Jika user ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Periksa peran user (role)
        if ($user['role'] === 'admin') {
            echo json_encode([
                "message" => "Login successful",
                "role" => "admin"
            ]);
        } else {
            echo json_encode([
                "message" => "Login successful",
                "role" => "client"
            ]);
        }
    } else {
        // Password salah
        http_response_code(401);
        echo json_encode(["message" => "Invalid password"]);
    }
} else {
    // Username tidak ditemukan
    http_response_code(404);
    echo json_encode(["message" => "User not found"]);
}

$sql->close();
$conn->close();
?>
