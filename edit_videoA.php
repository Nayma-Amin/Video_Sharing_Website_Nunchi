<?php
require_once('nunchi_database.php');
include('includes/header.php');

if(isset($_GET['v_id'])) {
    $video_id = $_GET['v_id'];

    $sql = "SELECT * FROM video WHERE v_id='$video_id'";
    $query = mysqli_query($con, $sql);
    if(mysqli_num_rows($query) > 0) {
        $videoInfo = mysqli_fetch_array($query);
    } else {
        echo "<script>alert('No Record Found');</script>";
        echo "<script>window.location.href='uploaded_videos.php';</script>";
        exit(0);
    }
} else {
    echo "<script>alert('Invalid Request');</script>";
    echo "<script>window.location.href='uploaded_videos.php';</script>";
    exit(0);
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Edit Video</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Edit Video Information</li>
    </ol>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Video</h4>
                </div>
                <div class="card-body">
                    <form action="update_video.php" method="POST">
                        <input type="hidden" name="video_id" value="<?= $videoInfo['v_id']; ?>">
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" value="<?= $videoInfo['title']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?= $videoInfo['description']; ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="video">Video</label>
                            <input type="text" name="video" class="form-control" value="<?= $videoInfo['video']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="thumbnail">Thumbnail</label>
                            <input type="text" name="thumbnail" class="form-control" value="<?= $videoInfo['thumbnail']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="likes">Likes</label>
                            <input type="number" name="likes" class="form-control" value="<?= $videoInfo['likes']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="dislikes">Dislikes</label>
                            <input type="number" name="dislikes" class="form-control" value="<?= $videoInfo['dislikes']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="views">Views</label>
                            <input type="number" name="views" class="form-control" value="<?= $videoInfo['views']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="topic">Topic</label>
                            <input type="text" name="topic" class="form-control" value="<?= $videoInfo['topic']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="upload_date">Upload Date</label>
                            <input type="date" name="upload_date" class="form-control" value="<?= $videoInfo['upload_date']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <button type="submit" name="update_video" class="btn btn-primary">Update Video</button>
                            <a href="uploaded_videos.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
