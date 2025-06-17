<?php
session_start();
require_once "nunchi_database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['video_id'])) {
        $v_id = intval($data['video_id']);
        
        if ($v_id > 0) {
            $query = "UPDATE video SET views = views + 1 WHERE v_id = '$v_id' LIMIT 1";
            if (mysqli_query($con, $query)) {
                echo json_encode(['success' => true]);
            } else {
                $error_message = mysqli_error($con);
                error_log("MySQL error: " . $error_message);
                echo json_encode(['success' => false, 'message' => $error_message]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid video ID']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Video ID missing']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
