<?php
require_once('nunchi_database.php');
include('includes/header.php');

function copyUserToUsers($userId, $con) {
    $sql = "INSERT INTO users (id, firstname, lastname, email, profile_picture, payment_status, created_at)
            SELECT id, firstname, lastname, email, profile_picture, payment_status, created_at
            FROM bins_user WHERE id = $userId";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $deleteSql = "DELETE FROM bins_user WHERE id = $userId";
        $deleteResult = mysqli_query($con, $deleteSql);
        return $deleteResult;
    } else {
        return false;
    }
}

if (isset($_POST['undo_delete_video'])) {
    $videoId = $_POST['video_id'];
    $userIdQuery = "SELECT id FROM users WHERE id = (SELECT id FROM bins_video WHERE v_id = $videoId)";
    $userIdResult = mysqli_query($con, $userIdQuery);
    
    if ($userIdRow = mysqli_fetch_assoc($userIdResult)) {
        $userId = $userIdRow['id'];
        if (copyVideoToVideo($videoId, $userId, $con)) {
            echo "<script>window.location.href = 'bins.php';</script>";
            exit();
        }
    } else {
        echo "User does not exist.";
        exit();
    }
}

function copyVideoToVideo($videoId, $userId, $con) {
    $sql = "INSERT INTO video (v_id, id, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date)
            SELECT v_id, $userId, title, description, video, thumbnail, likes, dislikes, views, topic, upload_date
            FROM bins_video WHERE v_id = $videoId";
    $result = mysqli_query($con, $sql);

    if ($result) {
        $deleteSql = "DELETE FROM bins_video WHERE v_id = $videoId";
        $deleteResult = mysqli_query($con, $deleteSql);
        return $deleteResult;
    } else {
        return false;
    }
}


function deleteUserFromBins($userId, $con) {
    $sql = "DELETE FROM bins_user WHERE id = $userId";
    $result = mysqli_query($con, $sql);
    return $result;
}

function deleteVideoFromBins($videoId, $con) {
    $sql = "DELETE FROM bins_video WHERE v_id = $videoId";
    $result = mysqli_query($con, $sql);
    return $result;
}

if (isset($_POST['undo_delete_user'])) {
    $userId = $_POST['user_id'];
    if (copyUserToUsers($userId, $con)) {
        echo "<script>window.location.href = 'bins.php';</script>";
        exit();
    }
}

if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    if (deleteUserFromBins($userId, $con)) {
        echo "<script>window.location.href = 'bins.php';</script>";
        exit();
    }
}

if (isset($_POST['delete_video'])) {
    $videoId = $_POST['video_id'];
    if (deleteVideoFromBins($videoId, $con)) {
        echo "<script>window.location.href = 'bins.php';</script>";
        exit();
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Nunchi Admin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">See beauty in everything</li>
        <li class="breadcrumb-item active">Bins</li>
    </ol>

    <style>
    thead,
    tbody,
    tfoot,
    tr,
    td,
    th {
      border-color: inherit;
      border-style: solid;
      border-width: 0;
    }
    
    .user-table td {
  word-wrap: break-word; 
  max-width: 200px; 
  white-space: nowrap; 
  overflow: hidden; 
  text-overflow: ellipsis;
}
  </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>User Management</h4>
                </div>
                <div class="card-body-video">
                    <table class="table table-bordered user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>FIRST-
                                    NAME</th>
                                <th>LAST-
                                    NAME</th>
                                <th>PASSWORD</th>
                                <th>EMAIL</th>
                                <th>PROFILE PICTURE</th>
                                <th>PAYMENT STATUS</th>
                                <th>UNDO DELETE</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql3 = "SELECT * FROM bins_user";
                            $query3 = mysqli_query($con, $sql3);
                            if (mysqli_num_rows($query3) > 0) {
                                while ($info = mysqli_fetch_array($query3)) {
                                    $id = $info['id'];
                                    $profile_picture = "profile_pictures/" . $info['profile_picture'];
                                    ?>
                                    <tr>
                                        <td><?= $info['id']; ?></td>
                                        <td><?= $info['firstname']; ?></td>
                                        <td><?= $info['lastname']; ?></td>
                                        <td><?= $info['password']; ?></td>
                                        <td><?= $info['email']; ?></td>
                                        <td><a href="id=<?php echo $id; ?>"><img src = "<?php echo $profile_picture; ?>" width=50px; height=60px;></a></td>
                                        <td><?= $info['payment_status']; ?></td>
                                        <td>
                                            <form action="bins.php" method="POST">
                                                <input type="hidden" name="user_id" value="<?= $info['id']; ?>">
                                                <button type="submit" name="undo_delete_user" class="btn btn-primary">Undo Delete</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="bins.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <input type="hidden" name="user_id" value="<?= $info['id']; ?>">
                                                <input type="hidden" name="reason" value="User requested account deletion"> <!-- Example reason -->
                                                <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr>";
                                echo "<td colspan='8'>No Data Found</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Video Management</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered video-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>USER ID</th>
                                <th>TITLE</th>
                                <th>DES-
                                    CRIPTION</th>
                                <th>VIDEO</th>
                                <th>THUMB-
                                    NAIL</th>
                                <th>LIKES</th>
                                <th>DIS-
                                    LIKES</th>
                                <th>VIEWS</th>
                                <th>TOPIC</th>
                                <th>UPLOAD DATE</th>
                                <th>UNDO DELETE</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql4 = "SELECT * FROM bins_video";
                            $query4 = mysqli_query($con, $sql4);
                            if (mysqli_num_rows($query4) > 0) {
                                while ($videoInfo = mysqli_fetch_array($query4)) {
                                    $v_id = $videoInfo['v_id'];
                                    $thumbnail = "thumbnail/" . $videoInfo['thumbnail'];
                                    ?>
                                    <tr>
                                        <td><?= $videoInfo['v_id']; ?></td>
                                        <td><?= $videoInfo['id']; ?></td>
                                        <td><?= $videoInfo['title']; ?></td>
                                        <td><?= $videoInfo['description']; ?></td>
                                        <td><?= $videoInfo['video']; ?></td>
                                        <td><a href="v_id=<?php echo $v_id; ?>"><img src = "<?php echo $thumbnail; ?>" width=70px; height=60px;></a></td>
                                        <td><?= $videoInfo['likes']; ?></td>
                                        <td><?= $videoInfo['dislikes']; ?></td>
                                        <td><?= $videoInfo['views']; ?></td>
                                        <td><?= $videoInfo['topic']; ?></td>
                                        <td><?= $videoInfo['upload_date']; ?></td>
                                        <td>
                                            <form action="bins.php" method="POST">
                                                <input type="hidden" name="video_id" value="<?= $videoInfo['v_id']; ?>">
                                                <button type="submit" name="undo_delete_video" class="btn btn-primary">Undo Delete</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="bins.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                                <input type="hidden" name="video_id" value="<?= $videoInfo['v_id']; ?>">
                                                <button type="submit" name="delete_video" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr>";
                                echo "<td colspan='12'>No Data Found</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
