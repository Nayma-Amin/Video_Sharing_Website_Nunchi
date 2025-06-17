<?php
session_start();
require_once "nunchi_database.php";

header('Content-Type: application/json');

if (!isset($_SESSION['auth'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$payment_status = $_SESSION['payment_status'];

if ($payment_status !== 'YES') {
    echo json_encode(['success' => false, 'message' => 'Subscription requires active payment status']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['subscribed_to_user_id'])) {
    $subscribed_to_user_id = intval($input['subscribed_to_user_id']);

    $check_query = "SELECT * FROM subscriptions WHERE user_id = $user_id AND subscribed_to_user_id = $subscribed_to_user_id";
    $check_result = mysqli_query($con, $check_query);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $delete_query = "DELETE FROM subscriptions WHERE user_id = $user_id AND subscribed_to_user_id = $subscribed_to_user_id";
        mysqli_query($con, $delete_query);
        $subscribed = false;

        $update_count_query = "UPDATE users SET subscribers = subscribers - 1 WHERE id = $subscribed_to_user_id";
        mysqli_query($con, $update_count_query);
    } else {
        
        $insert_query = "INSERT INTO subscriptions (user_id, subscribed_to_user_id) VALUES ($user_id, $subscribed_to_user_id)";
        mysqli_query($con, $insert_query);
        $subscribed = true;

        
        $update_count_query = "UPDATE users SET subscribers = subscribers + 1 WHERE id = $subscribed_to_user_id";
        mysqli_query($con, $update_count_query);
    }

    $count_query = "SELECT subscribers FROM users WHERE id = $subscribed_to_user_id";
    $count_result = mysqli_query($con, $count_query);
    $subscribers_row = mysqli_fetch_assoc($count_result);

    echo json_encode(['success' => true, 'subscribed' => $subscribed, 'subscribers' => $subscribers_row['subscribers']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}
?>
