<?php
require_once('nunchi_database.php');
include('includes/header.php');

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];
    $admin = "SELECT * FROM admins WHERE id = $admin_id";
    $query = mysqli_query($con, $admin);

    if (mysqli_num_rows($query) > 0) {
        $admin_data = mysqli_fetch_array($query);
        ?>

        <div class="container-fluid px-4">
            <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Nunchi Admin Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">See beauty in everything</li>
                <li class="breadcrumb-item active">Edit Admin</li>
            </ol>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Edit Admin</h4>
                        </div>
                        <div class="card-body">
                            <form action="update_admin.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $admin_data['id']; ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Username</label>
                                        <input type="text" name="username" value="<?= $admin_data['username']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Email</label>
                                        <input type="email" name="email" value="<?= $admin_data['email']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Password</label>
                                        <input type="text" name="password" value="<?= $admin_data['password']; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Profile Picture</label>
                                        <input type="file" name="profile_picture" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" name="update_admin" class="btn btn-primary">Update Admin</button>
                                        <a href="admins.php" class="btn btn-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo "<script>alert('Admin not found!')</script>";
        exit();
    }
}
?>

<?php
include('includes/footer.php');
?>
