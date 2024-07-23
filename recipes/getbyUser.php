<?php
require '../connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $stmt = $conn->prepare('SELECT * FROM recipes WHERE user_id = ?');
        $stmt->bind_param('i', $user_id);
        try {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $recipes = [];
                while ($row = $result->fetch_assoc()) {
                    $recipes[] = $row;
                }
                echo json_encode(["status" => "success", "data" => $recipes]);
            } else {
                echo json_encode(["status" => "error", "message" => "No recipes found for user ID: " . $user_id]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "No user_id provided"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
$conn->close();
?>
