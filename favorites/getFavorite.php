<?php
require '../connection.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Credentials: true');

session_start(); // Ensure the session is started

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Retrieve user_id from session

        // Get the list of favorite recipe IDs
        $stmt = $conn->prepare('SELECT recipe_id FROM favorites WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);

        try {
            $stmt->execute();
            $result = $stmt->get_result();
            $favoriteIds = [];
            while ($row = $result->fetch_assoc()) {
                $favoriteIds[] = $row['recipe_id'];
            }

            if (count($favoriteIds) > 0) {
                // Fetch details of favorite recipes
                $ids = implode(',', $favoriteIds);
                $stmt = $conn->prepare("SELECT * FROM recipes WHERE id IN ($ids)");
                $stmt->execute();
                $result = $stmt->get_result();
                $favorites = [];
                while ($row = $result->fetch_assoc()) {
                    $favorites[] = $row;
                }
                echo json_encode(["status" => "success", "favorites" => $favorites]);
            } else {
                echo json_encode(["status" => "success", "favorites" => []]);
            }
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
