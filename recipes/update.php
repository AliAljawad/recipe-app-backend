<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['ingredients']) && isset($_POST['steps']) && isset($_POST['image_url'])) {
        $id = intval($_POST['id']);
        $title = $_POST['title'];
        $ingredients = $_POST['ingredients'];
        $steps = $_POST['steps'];
        $image_url = $_POST['image_url'];

        $stmt = $conn->prepare('UPDATE recipes SET title = ?, ingredients = ?, steps = ?, image_url = ? WHERE id = ?');
        $stmt->bind_param('ssssi', $title, $ingredients, $steps, $image_url, $id);

        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "Recipe updated successfully", "status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Recipe update failed or no changes made"]);
            }
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
