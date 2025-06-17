<?php
require_once('nunchi_database.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Nunchi Admin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">See beauty in everything</li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    
    <style>
    .user-table td {
  word-wrap: break-word; 
  max-width: 180px; 
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
                <div class="card-body">
                    <table class="table table-bordered user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>FIRST-
                                    NAME</th>
                                <th>LAST-
                                    NAME</th>
                                <th>EMAIL</th>
                                <th>PASSWORD</th>
                                <th>PROFILE PICTURE</th>
                                <th>PAYMENT STATUS</th>
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql3 = "SELECT * FROM users";
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
                                        <td><?= $info['email']; ?></td>
                                        <td><?= $info['password']; ?></td>
                                        <td><a href="id=<?php echo $id; ?>"><img src = "<?php echo $profile_picture; ?>" width=50px; height=60px;></a></td>
                                        <td><?= $info['payment_status']; ?></td>
                                        <td><a href="edit-userA.php?id=<?= $info['id']; ?>" class="btn btn-success">Edit</a></td>
                                        <td>
                                            <form action="delete_userA.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
</div>

<?php
include('includes/footer.php');
?>
