<?php
require '../connection.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['fullname']) && isset($data['email']) && isset($data['password'])) {
        $fullname = $data['fullname'];
        $email = $data['email'];
        $password = $data['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO Users (fullname, email, password_hash) VALUES (?, ?, ?);');
        $stmt->bind_param('sss', $fullname, $email, $hashed_password);

        try {
            $stmt->execute();
            $user_id = $conn->insert_id;  // Get the last inserted ID
            echo json_encode(["message" => "User created successfully", "status" => "success", "user_id" => $user_id]);
        } catch (Exception $e) {
            echo json_encode(["error" => $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "Please ensure all fields are filled"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
$conn->close();
?>