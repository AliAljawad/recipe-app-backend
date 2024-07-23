<?php
require '../connection.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['recipe_id'])) {
        $recipe_id = intval($_GET['recipe_id']);

        $stmt = $conn->prepare('
            SELECT comments.comment, users.fullname 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.recipe_id = ?
        ');
        $stmt->bind_param('i', $recipe_id);

        try {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $comments = [];
                while ($row = $result->fetch_assoc()) {
                    $comments[] = $row;
                }
                echo json_encode(["status" => "success", "comments" => $comments]);
            } else {
                echo json_encode(["status" => "error", "message" => "No comments found for recipe ID: " . $recipe_id]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No recipe ID provided"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Wrong request method"]);
}
$conn->close();
?>
