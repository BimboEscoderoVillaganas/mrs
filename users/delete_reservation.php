<?php
include_once('../config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];

    $db = new Database();
    $sql = "UPDATE reservation SET status = 'deleted' WHERE r_id = ?";
    $result = $db->updateRow($sql, [$reservation_id]);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
