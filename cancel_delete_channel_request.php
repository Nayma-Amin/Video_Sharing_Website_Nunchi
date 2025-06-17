<?php
session_start();
include 'nunchi_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_delete_channel'])) {
    if (!isset($_SESSION['auth'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $delete_user_sql = "DELETE FROM bins_user WHERE id = ?";
    $stmt = $con->prepare($delete_user_sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel delete channel request']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
