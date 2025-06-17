<?php
session_start();
include 'nunchi_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_id'])) {
    $video_id = intval($_POST['video_id']);
    $user_id = $_SESSION['user_id'];

    $con->begin_transaction();

    try {
        $move_sql = "INSERT INTO bins_video (v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date, deleted_at)
                     SELECT v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date, NOW()
                     FROM video
                     WHERE v_id = $video_id AND id = $user_id";
        $move_result = $con->query($move_sql);

        if ($move_result) {
            $delete_sql = "DELETE FROM video WHERE v_id = $video_id AND id = $user_id";
            $delete_result = $con->query($delete_sql);

            if ($delete_result) {
                $con->commit();
                echo "<script>alert('Video deleted successfully');</script>";
                echo "<script>window.location.href='uploaded_videos.php';</script>";
            } else {
                $con->rollback();
                echo "<script>alert('Failed to delete video');</script>";
                echo "<script>window.location.href='uploaded_videos.php';</script>";
            }
        } else {
            $con->rollback();
            echo "<script>alert('Failed to copy video to bins');</script>";
            echo "<script>window.location.href='uploaded_videos.php';</script>";
        }

    } catch (Exception $e) {
        $con->rollback();
        echo "<script>alert('Transaction failed: " . addslashes($con->error) . "');</script>";
        echo "<script>window.location.href='uploaded_videos.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Request');</script>";
    echo "<script>window.location.href='uploaded_videos.php';</script>";
}
?>
