<?php
require_once('nunchi_database.php');

if (isset($_POST['update_admin'])) {
    $admin_id = $_POST['id'];
    if (empty($admin_id)) {
        echo "<script>alert('Admin ID is missing!')</script>";
        exit();
    }

    $update_fields = [];

    if (!empty($_POST['username'])) {
        $username = mysqli_real_escape_string($con, $_POST['username']);
        $update_fields[] = "username = '$username'";
    }

    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $update_fields[] = "email = '$email'";
    }

    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $update_fields[] = "password = '$password'";
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $profile_picture_temp_loc = $_FILES['profile_picture']['tmp_name'];
        $profile_picture_store = "profile_pictures/" . $profile_picture;
        move_uploaded_file($profile_picture_temp_loc, $profile_picture_store);
        $profile_picture = mysqli_real_escape_string($con, $profile_picture);
        $update_fields[] = "profile_picture = '$profile_picture'";
    }

    if (!empty($update_fields)) {
        $sql4 = "UPDATE admins SET " . implode(', ', $update_fields) . " WHERE id = '$admin_id'";
        $query4 = mysqli_query($con, $sql4);

        if ($query4) {
            echo "<script>alert('Admin updated successfully!')</script>";
            header("Location: admins.php");
            exit(); 
        } else {
            echo "<script>alert('Admin not updated!')</script>";
            echo "SQL Error: " . mysqli_error($con);
            echo "SQL Query: " . $sql4;
        }
    } else {
        echo "<script>alert('No fields to update!')</script>";
    }
}
?>
