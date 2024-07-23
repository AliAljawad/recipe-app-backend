<?php
require '../connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare('SELECT * FROM recipes WHERE id = ?');
        $stmt->bind_param('i', $id);
        try {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $recipe = $result->fetch_assoc();
                echo json_encode(["status" => "success", "data" => $recipe]);
            } else {
                echo json_encode(["status" => "error", "message" => "No recipe found with ID: " . $id]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "No ID provided"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
$conn->close();
?>
