<?php
require '../connection.php';
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: true');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['title']) && isset($_POST['ingredients']) && isset($_POST['steps']) && isset($_FILES['image'])) {
        $user_id = $_POST['user_id'];
        $title = $_POST['title'];
        $ingredients = $_POST['ingredients'];
        $steps = $_POST['steps']; // Steps should be stored as a plain text
        $image = $_FILES['image'];

        // Handle image upload
        $imageDir = 'uploads/';
        $imagePath = $imageDir . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Prepare SQL statement
            $stmt = $conn->prepare('INSERT INTO recipes (user_id, title, ingredients, steps, image_url) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('issss', $user_id, $title, $ingredients, $steps, $imagePath);

            try {
                $stmt->execute();
                echo json_encode(["message" => "Recipe created successfully", "status" => "success"]);
            } catch (Exception $e) {
                echo json_encode(["error" => $stmt->error]);
            }
        } else {
            echo json_encode(["error" => "Failed to upload image"]);
        }
    } else {
        echo json_encode(["error" => "Please ensure all fields are filled"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
$conn->close();
?>
