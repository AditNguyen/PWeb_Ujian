<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require "db.php";

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input"]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$sql = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$sql->bind_param("ss", $username, $hashedPassword);

if ($sql->execute()) {
    echo json_encode(["message" => "User registered successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Error registering user"]);
}
?>
