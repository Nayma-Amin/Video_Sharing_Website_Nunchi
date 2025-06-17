<?php
session_start();
include 'nunchi_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST data: " . print_r($_POST, true);

    if (isset($_POST['delete_channel_confirm'])) {
        if (!isset($_SESSION['auth'])) {
            echo "User not authenticated";
            exit;
        }

        $user_id = $_SESSION['user_id'];

        if (!isset($_POST['reason']) || empty($_POST['reason'])) {
            echo "Reason is required";
            exit;
        }

        $reason = $_POST['reason'];

        $fetch_user_sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $con->prepare($fetch_user_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user_data = $user_result->fetch_assoc();
        echo "User data: " . json_encode($user_data);

        $insert_reason_sql = "INSERT INTO bins_user (id, firstname, lastname, email, password, profile_picture, created_at, payment_status, reason)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insert_reason_sql);
        $stmt->bind_param("issssbsss", $user_data['id'], $user_data['firstname'], 
        $user_data['lastname'], $user_data['email'], $user_data['password'], $user_data['profile_picture'], 
        $user_data['created_at'], $user_data['payment_status'], $reason);

        if ($stmt->execute()) {
            echo "Delete Channel Request submitted successfully. Your channel will be deleted within 7 days if you don't cancel the request.";
        } else {
            echo "Failed to send Delete Channel Request.";
        }
    } else {
        echo "Invalid request: delete_channel_confirm not set";
    }
} else {
    echo "Invalid request method: " . $_SERVER['REQUEST_METHOD'];
}
?>
