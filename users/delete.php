<?php
require "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST['id'];

    $stmt = $conn->prepare('DELETE FROM Users WHERE id=?');
    $stmt->bind_param('i', $id);

    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(["message" => "User deleted", "status" => "success"]);
        } else {
            echo json_encode(["message" => "No user found with the given ID", "status" => "error"]);
        }
    } catch (Exception $e) {
        echo json_encode(["error" => $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Wrong request method"]);
}
?>