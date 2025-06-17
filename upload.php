<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video - Nunchi</title>
    <style>
      .upload{
    background-color: red;
    color: #fff;
    padding: 8px 70px;
    border: 0;
    outline: 0;
    border-radius: 4px;
    cursor: hand;
    font-size: small;
    width: 10%;
    float: left;
      }
      
      .reset{
        background-color: red;
    color: #fff;
    padding: 8px 70px;
    border: 0;
    outline: 0;
    border-radius: 4px;
    cursor: hand;
    font-size: small;
    width: 10%;

      }

      .upload_container{
        width: 900px;
        height: 500px;
        background-color: #fff;
        margin: 0 auto;
        margin-top: 10px;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
      }
      .up{
        float: left;
        
      }
      .set{
        float: right;
      }

    </style>
</head>
<body>
    <div class="upload_container">
        <h2>Upload Video</h2>

        <form class="" action="upload.php" method="post" enctype="multipart/form-data">
            <label for="video">Select Video:</label>
            <input type="file" name="video" values="" placeholder="Select a video file" required><br><br>
            <label for="title">Video Title:</label>
            <input type="text" name="title" id="title" values="" placeholder="Enter Title" required><br><br>
            <label for="topic">Video Topic:</label>
            <input type="text" name="topic" id="topic" values="" placeholder="Enter Topic" required><br><br>
            <label for="thumbnail">Select Thumbnail:</label>
            <input type="file" name="thumbnail" values="" placeholder="Select a image file" required><br><br>
            <label for="description">Video Description:</label>
            <textarea name="description" id="description" cols="80" rows="8" values="" placeholder="Enter description" required></textarea><br><br>
            
            <div class="up">
                 <button type="submit" name="upload" class="upload">Upload     </button>
    </div>
    <div class="set">
            <button type="reset" name="reset" class="reset">Reset</button>
    </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
<?php
require_once 'nunchi_database.php';
session_start();

if (isset($_POST['upload'])) {
    $video = $_FILES['video']['name'];
    $thumbnail = $_FILES['thumbnail']['name'];
    $title = $_POST['title'];
    $topic = $_POST['topic'];
    $description = $_POST['description'];
    
    $thumbnail_type = $_FILES['thumbnail']['type'];
    $thumbnail_size = $_FILES['thumbnail']['size'];
    $thumbnail_temp_loc = $_FILES['thumbnail']['tmp_name'];
    $thumbnail_store = "thumbnail/" . $thumbnail;
    move_uploaded_file($thumbnail_temp_loc, $thumbnail_store);
    
    $video_type = $_FILES['video']['type'];
    $video_size = $_FILES['video']['size'];
    $video_temp_loc = $_FILES['video']['tmp_name'];
    $video_store = "video/" . $video;
    move_uploaded_file($video_temp_loc, $video_store);

   
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
           $sql = "INSERT INTO video (id, title, topic, description, thumbnail, video) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "isssss", $user_id, $title, $topic, $description, $thumbnail, $video);

$query = mysqli_stmt_execute($stmt);

if ($query) {
    echo "<script>alert('Video uploaded successfully.');</script>";
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . mysqli_error($con);
}
    }
  }
?>