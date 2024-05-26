<?php
include_once('../config.php');
$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    $sql = "UPDATE users SET user_fN = ?, user_mN = ?, user_lN = ?, user_address = ?, tour_contact = ?, user_name = ?, user_pass = ?, user_type = ? WHERE user_id = ?";
    $result = $db->updateRow($sql, [$firstName, $middleName, $lastName, $address, $contact, $username, $password, $userType, $userId]);

    if ($result) {
        echo json_encode(['success' => 'User updated successfully']);
    } else {
        echo json_encode(['error' => 'Failed to update user']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
