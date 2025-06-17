<?php
session_start();
require_once "nunchi_database.php"; 

function get_likes($v_id) {
    global $con;
    $query = "SELECT likes FROM video WHERE v_id = '$v_id' LIMIT 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['likes'];
}

function get_dislikes($v_id) {
    global $con;
    $query = "SELECT dislikes FROM video WHERE v_id = '$v_id' LIMIT 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['dislikes'];
}

function update_rating($v_id, $action) {
    global $con;

    if ($action !== 'like' && $action !== 'dislike') {
        return ['success' => false, 'message' => 'Invalid action'];
    }

    if (!isset($_SESSION['user_id'])) {
        return ['success' => false, 'message' => 'User not logged in'];
    }

    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM user_likes WHERE user_id = '$user_id' AND v_id = '$v_id'";
    $result = mysqli_query($con, $query);
    $user_action = mysqli_fetch_assoc($result);

    if ($user_action) {
        $current_action = $user_action['action'];
        $query = "DELETE FROM user_likes WHERE user_id = '$user_id' AND v_id = '$v_id'";
        mysqli_query($con, $query);
        $query = "UPDATE video SET {$current_action}s = {$current_action}s - 1 WHERE v_id = '$v_id' LIMIT 1";
        mysqli_query($con, $query);
    } else {
        $query = "INSERT INTO user_likes (user_id, v_id, action) VALUES ('$user_id', '$v_id', '$action')";
        mysqli_query($con, $query);
        $query = "UPDATE video SET {$action}s = {$action}s + 1 WHERE v_id = '$v_id' LIMIT 1";
        mysqli_query($con, $query);
    }

    return [
        'likes' => get_likes($v_id),
        'dislikes' => get_dislikes($v_id),
        'success' => true
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['v_id']) && isset($data['action'])) {
        $v_id = mysqli_real_escape_string($con, $data['v_id']);
        $action = mysqli_real_escape_string($con, $data['action']);
        $result = update_rating($v_id, $action);
        echo json_encode($result);
        exit;
    }
}
?>


