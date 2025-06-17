<?php
require_once('nunchi_database.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Nunchi Admin Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">See beauty in everything</li>
        <li class="breadcrumb-item active">Admins</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Admin Management</h4>
                    <a href="add_admin.php" class="btn btn-primary float-end">Add Admin</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>USERNAME</th>
                                <th>EMAIL</th>
                                <th>PROFILE</th>
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql3 = "SELECT * FROM admins";
                            $query3 = mysqli_query($con, $sql3);
                            if (mysqli_num_rows($query3) > 0) {
                                
                                while ($info = mysqli_fetch_array($query3)) {
                                    $id = $info['id'];
                                    $profile = "admin_profiles/" . $info['profile'];
                                    ?>
                                    <tr>
                                        <td><?php echo $info['id']; ?></td>
                                        <td><?php echo $info['username']; ?></td>
                                        <td><?php echo $info['email']; ?></td>
                                        <td><a href="id=<?php echo $id; ?>"><img src = "<?php echo $profile; ?>" width=50px; height=60px;></a></td>
                                        <td><a href="admin_edit.php?id=<?php echo $info['id']; ?>" class="btn btn-success">Edit</a></td>
                                        <td>
                                            <form action="delete_admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                                <input type="hidden" name="admin_id" value="<?php echo $info['id']; ?>">
                                                <button type="submit" name="delete_admin" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5'>No Data Found</td></tr>";
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
