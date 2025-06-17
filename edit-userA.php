<?php
require_once('nunchi_database.php')
?>
<?php
include('includes/header.php');

?>

<div class="container-fluid px-4">
                        <h1 class="mt-4"><img src="imagesWS/logoWS.png" class="logo"> Nunchi Admin Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">See beauty in everything</li>
                            <li class="breadcrumb-item active">User Details</li>
                        </ol>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Edit User</h4>

</div>
<div class="card-body">

<?php

if(isset($_GET['id']))
    {
        $user_id = $_GET['id'];
        $users = "SELECT * FROM users WHERE id = $user_id";
        $query = mysqli_query($con, $users);

        if(mysqli_num_rows($query) > 0){
             
            foreach($query as $user){

            ?>

    <form action="update_userA.php" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="">First Name</label>
                <input type="text" name="firstname" value="<?=$user['firstname'];?>" class="form-control">
</div>
<div class="col-md-6 mb-3">
                <label for="">Last Name</label>
                <input type="text" name="lastname" value="<?=$user['lastname'];?>" class="form-control">
</div>
<div class="col-md-6 mb-3">
                <label for="">Email</label>
                <input type="text" name="email" value="<?=$user['email'];?>" class="form-control">
</div>

<div class="col-md-6 mb-3">
    <label for="">Payment Status</label>
    <input type="text" name="payment_status" value="<?=$user['payment_status'];?>" class="form-control">
    </div>
<div class="col-md-6 mb-3">
                <label for="">Password</label>
                <input type="text" name="password" value="<?=$user['password'];?>" class="form-control">
</div>
<div class="col-md-6 mb-3">
                <label for="">Profile Picture</label>
                <input type="file" name="profile_picture" value="<?=$user['profile_picture'];?>" class="form-control">
</div>

<div class="col-md-6 mb-3">
    <button type="submit" name="update_user" class="btn btn-primary">Update Profile</button>
    <a href="registered_users.php" class="btn btn-danger">Cancel</a>
</div>
</form>
<?php
            }
        }
        else{
            echo "<script>alert('User not found!')</script>";
            exit();
    }

    }

?>

</div>
</div>
</div>
</div>
</div>
