<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
require '../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['email']) && isset($data['password'])) {
        $email = $data['email'];
        $password = $data['password'];

        $stmt = $conn->prepare('SELECT id, password_hash FROM Users WHERE email = ?');
        $stmt->bind_param('s', $email);

        try {
            $stmt->execute();
            $stmt->bind_result($user_id, $password_hash);
            if ($stmt->fetch() && password_verify($password, $password_hash)) {
                $_SESSION['user_id'] = $user_id;  // Save user ID in session
                echo json_encode(["status" => "success", "user_id" => $user_id]);
            } else {
                echo json_encode(["status" => "error", "error" => "Invalid email or password"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "error" => $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "error" => "Please provide email and password"]);
    }
} else {
    echo json_encode(["status" => "error", "error" => "Wrong request method"]);
}
$conn->close();
?>
