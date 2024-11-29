<?php
//koneksi nang db
$host = "localhost";
$db_name = "sekolah";
$username_db = "root";
$password_db = "";
// apikey
$valid_api_key = "a3b5f7c8d9e0f1a2b3c4d5e6f7g8h9i0";
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username_db, $password_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit();
}
header("Content-Type: application/json");
if (!isset($_GET['apikey']) || $_GET['apikey'] !== $valid_api_key) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or missing API key"]);
    exit();
}
// validasi method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['username'])) {
        $username_param = $_GET['username'];
        $stmt = $conn->prepare("SELECT username, password, level FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username_param);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Username parameter is required"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}
?>