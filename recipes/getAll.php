<?php
require '../connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => '', 'data' => []];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $conn->prepare('SELECT * FROM recipes');
    try {
        $stmt->execute();
        $result = $stmt->get_result();
        $recipes = [];
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
        $response['status'] = 'success';
        $response['data'] = $recipes;
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}
echo json_encode($response);
?>