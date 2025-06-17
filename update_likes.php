<?php
session_start();
require_once "nunchi_database.php";


if (!isset($_SESSION['auth'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}


$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['video_id']) && isset($data['action']) && isset($_SESSION['user_id'])) {
    $v_id = intval($data['video_id']);
    $action = $data['action'];
    $user_id = $_SESSION['user_id'];
    
    
    if ($action !== 'like' && $action !== 'dislike') {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit();
    }

    
    $check_query = "SELECT * FROM user_likes WHERE video_id = $v_id AND user_id = $user_id";
    $check_result = mysqli_query($con, $check_query);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
    
        $row = mysqli_fetch_assoc($check_result);
        $current_action = $row['action'];

        if ($current_action === $action) {
            
            $delete_query = "DELETE FROM user_likes WHERE video_id = $v_id AND user_id = $user_id";
            if (!mysqli_query($con, $delete_query)) {
                echo json_encode(['success' => false, 'message' => 'Error deleting like/dislike: ' . mysqli_error($con)]);
                exit();
            }
        } else {
        
            $update_query = "UPDATE user_likes SET action = '$action' WHERE video_id = $v_id AND user_id = $user_id";
            if (!mysqli_query($con, $update_query)) {
                echo json_encode(['success' => false, 'message' => 'Error updating like/dislike: ' . mysqli_error($con)]);
                exit();
            }
        }
    } else {
        
        $insert_query = "INSERT INTO user_likes (video_id, user_id, action) VALUES ($v_id, $user_id, '$action')";
        if (!mysqli_query($con, $insert_query)) {
            echo json_encode(['success' => false, 'message' => 'Error inserting like/dislike: ' . mysqli_error($con)]);
            exit();
        }
    }

    $likes_query = "SELECT COUNT(*) AS likes FROM user_likes WHERE video_id = $v_id AND action = 'like'";
    $dislikes_query = "SELECT COUNT(*) AS dislikes FROM user_likes WHERE video_id = $v_id AND action = 'dislike'";
    $likes_result = mysqli_query($con, $likes_query);
    $dislikes_result = mysqli_query($con, $dislikes_query);

    if ($likes_result && $dislikes_result) {
        $likes_row = mysqli_fetch_assoc($likes_result);
        $dislikes_row = mysqli_fetch_assoc($dislikes_result);
        $update_video_query = "UPDATE video SET likes = {$likes_row['likes']}, dislikes = {$dislikes_row['dislikes']} WHERE v_id = $v_id";
        if (!mysqli_query($con, $update_video_query)) {
            echo json_encode(['success' => false, 'message' => 'Error updating video table: ' . mysqli_error($con)]);
            exit();
        }

        echo json_encode(['success' => true, 'likes' => $likes_row['likes'], 'dislikes' => $dislikes_row['dislikes']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error fetching likes/dislikes']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}
?>
