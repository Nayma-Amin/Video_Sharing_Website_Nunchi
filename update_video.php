<?php
require_once('nunchi_database.php');

if(isset($_POST['update_video'])) {
    $video_id = $_POST['video_id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $video = mysqli_real_escape_string($con, $_POST['video']);
    $thumbnail = mysqli_real_escape_string($con, $_POST['thumbnail']);
    $likes = mysqli_real_escape_string($con, $_POST['likes']);
    $dislikes = mysqli_real_escape_string($con, $_POST['dislikes']);
    $views = mysqli_real_escape_string($con, $_POST['views']);
    $topic = mysqli_real_escape_string($con, $_POST['topic']);
    $upload_date = mysqli_real_escape_string($con, $_POST['upload_date']);

    $sql = "UPDATE video SET title='$title', description='$description', video='$video', thumbnail='$thumbnail', likes='$likes', dislikes='$dislikes', views='$views', topic='$topic', upload_date='$upload_date' WHERE v_id='$video_id'";

    if(mysqli_query($con, $sql)) {
        echo "<script>alert('Video updated successfully');</script>";
        echo "<script>window.location.href='uploaded_videos.php';</script>";
    } else {
        echo "<script>alert('Failed to update video');</script>";
        echo "<script>window.location.href='edit-videoA.php?v_id=$video_id';</script>";
    }
} else {
    echo "<script>alert('Invalid Request');</script>";
    echo "<script>window.location.href='uploaded_videos.php';</script>";
}
?>
