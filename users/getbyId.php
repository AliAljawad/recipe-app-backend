<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $stmt = $conn->prepare('SELECT * FROM Users WHERE id = ?');
        $stmt->bind_param('i', $id);

        try {
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                echo json_encode(["status" => "success", "data" => $user]);
            } else {
                echo json_encode(["status" => "error", "message" => "No user found with ID: " . $id]);
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
