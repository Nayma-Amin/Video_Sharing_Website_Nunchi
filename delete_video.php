<?php
session_start();
include 'nunchi_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['v_id'])) {
    $v_id = intval($_POST['v_id']);
    $user_id = $_SESSION['user_id'];

    $con->begin_transaction();

    try {
        $move_sql = "INSERT INTO bins_video (v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date, deleted_at)
                     SELECT v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date, NOW()
                     FROM video
                     WHERE v_id = $v_id AND id = $user_id";
        $move_result = $con->query($move_sql);

        if ($move_result) {
            $delete_sql = "DELETE FROM video WHERE v_id = $v_id AND id = $user_id";
            $delete_result = $con->query($delete_sql);

            if ($delete_result) {
                $con->commit();
                echo json_encode(['status' => 'success']);
            } else {
                $con->rollback();
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete video']);
            }
        } else {
            $con->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Failed to move video to bin']);
        }

    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $con->error]);
    }
}
?>
