<?php
session_start();
require_once 'nunchi_database.php';

if (isset($_POST['delete_user'])) {
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $reason = mysqli_real_escape_string($con, $_POST['reason']); 

    mysqli_begin_transaction($con);

    try {
        $sql_copy_user = "INSERT INTO bins_user (id, firstname, lastname, email, password, profile_picture, reason, created_at, payment_status, deleted_at)
                          SELECT id, firstname, lastname, email, password, profile_picture, '$reason', created_at, payment_status, NOW()
                          FROM users WHERE id = '$user_id'";
        mysqli_query($con, $sql_copy_user);

        $sql_copy_videos = "INSERT INTO bins_video (v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date, deleted_at)
                            SELECT v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date, NOW()
                            FROM video WHERE id = '$user_id'";
        mysqli_query($con, $sql_copy_videos);

        $sql_delete_videos = "DELETE FROM video WHERE id = '$user_id'";
        mysqli_query($con, $sql_delete_videos);

        $sql_delete_user = "DELETE FROM users WHERE id = '$user_id'";
        mysqli_query($con, $sql_delete_user);

        mysqli_commit($con);

        echo "<script>alert('User deleted successfully.');</script>";
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('Failed to delete user.');</script>";
    }

    header("Location: registered_users.php");
    exit();
} else {
    header("Location: registered_users.php");
    exit();
}
?>
