<?php
require '../connection.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Credentials: true');

session_start(); // Ensure the session is started

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Retrieve user_id from session
        $data = json_decode(file_get_contents('php://input'), true);
        $recipe_id = $data['recipe_id'];

        $stmt = $conn->prepare('INSERT INTO favorites (user_id, recipe_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $user_id, $recipe_id);

        try {
            $stmt->execute();
            echo json_encode(["status" => "success", "message" => "Favorite added"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
$conn->close();
?>
