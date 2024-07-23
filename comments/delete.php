<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        $stmt = $conn->prepare('DELETE FROM comments WHERE id = ?');
        $stmt->bind_param('i', $id);

        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "Comment deleted successfully", "status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "No comment found with the given ID"]);
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $stmt->error]);
        }
    } else {
        echo json_encode(["error" => "Please provide an ID"]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
$conn->close();
?>
