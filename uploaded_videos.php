<?php
require_once('nunchi_database.php');
include('includes/header.php');
?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Nunchi Admin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">See beauty in everything</li>
        <li class="breadcrumb-item active">Videos</li>
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
    td {
  word-wrap: break-word;
  max-width: 80px; 
    }
  </style>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Video Management</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>VIDEO ID</th>
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
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql4 = "SELECT * FROM video";
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
                                        <td><a href="edit_videoA.php?v_id=<?= $videoInfo['v_id']; ?>" class="btn btn-success">Edit</a></td>
                                        <td>
                                        <form action="delete_videoA.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                               <input type="hidden" name="video_id" value="<?= $videoInfo['v_id']; ?>">
                                               <input type="hidden" name="reason" value="User requested video deletion"> <!-- Example reason -->
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
