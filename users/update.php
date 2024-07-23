<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['id']) && isset($_POST['fullname']) && isset($_POST['email'])&& isset(($_POST['password']))) {
        $id = intval($_POST['id']);
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('UPDATE Users SET fullname = ?, email = ?, password_hash=? WHERE id = ?');
        $stmt->bind_param('sssi', $fullname, $email,$hashed_password ,$id);

        try {
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo json_encode(["message" => "User updated successfully", "status" => "success"]);
            } else {
                echo json_encode(["status" => "error", "message" => "User update failed or no changes made"]);
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
