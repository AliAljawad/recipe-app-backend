<?php
require '../connection.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['user_id']) && isset($_POST['recipe_id']) && isset($_POST['comment'])) {
        $user_id = intval($_POST['user_id']);
        $recipe_id = intval($_POST['recipe_id']);
        $comment = $_POST['comment'];

        $stmt = $conn->prepare('INSERT INTO comments (user_id, recipe_id, comment) VALUES (?, ?, ?)');
        $stmt->bind_param('iis', $user_id, $recipe_id, $comment);

        try {
            $stmt->execute();
            echo json_encode(["message" => "Comment added successfully", "status" => "success"]);
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
