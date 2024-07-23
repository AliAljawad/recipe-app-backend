<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['id']) && isset($_POST['comment'])) {
        $id = intval($_POST['id']);
        $comment = $_POST['comment'];

        $stmt = $conn->prepare('UPDATE comments SET comment = ? WHERE id = ?');
        $stmt->bind_param('si', $comment, $id);

        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "Comment updated successfully", "status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Comment update failed or no changes made"]);
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
