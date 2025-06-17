<?php
session_start();
require 'nunchi_database.php'; 

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SESSION['payment_status'] !== 'YES') {
    echo json_encode(['success' => false, 'message' => 'Payment status not valid for download']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['video_id']) && !empty($input['video_id'])) {
    $videoId = $input['video_id'];
    $userId = $_SESSION['user_id'];

    $query = $con->prepare("SELECT * FROM video WHERE v_id = ?");
    $query->bind_param('i', $videoId);
    $query->execute();
    $result = $query->get_result();
    $video = $result->fetch_assoc();

    if ($video) {
        $sourceFilePath = 'video/' . $video['video'];
        $destinationFilePath = 'downloads/' . $video['video'];

        if (!file_exists('downloads')) {
            mkdir('downloads', 0777, true);
        }

        if (copy($sourceFilePath, $destinationFilePath)) {
        
            $query = $con->prepare("INSERT INTO downloads (user_id, video_id) VALUES (?, ?)");
            $query->bind_param('ii', $userId, $videoId);
            if ($query->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $query->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File copy error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Video not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
