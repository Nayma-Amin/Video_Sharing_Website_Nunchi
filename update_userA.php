<?php
require_once 'nunchi_database.php';

if (isset($_POST['update_user'])) {
    $user_id = $_POST['id'];
    if (empty($user_id)) {
        echo "<script>alert('User ID is missing!')</script>";
        exit();
    }

    $update_fields = [];

    if (!empty($_POST['firstname'])) {
        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
        $update_fields[] = "firstname = '$firstname'";
    }

    if (!empty($_POST['lastname'])) {
        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
        $update_fields[] = "lastname = '$lastname'";
    }

    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $update_fields[] = "email = '$email'";
    }

    if (!empty($_POST['payment_status'])) {
        $payment_status = mysqli_real_escape_string($con, $_POST['payment_status']);
        $update_fields[] = "payment_status = '$payment_status'";
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
        $sql4 = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = '$user_id'";
        $query4 = mysqli_query($con, $sql4);

        if ($query4) {
            echo "<script>alert('User updated successfully!')</script>";
            header("Location: registered_users.php");
            exit();
        } else {
            echo "<script>alert('User not updated!')</script>";
            echo "SQL Error: " . mysqli_error($con);
            echo "SQL Query: " . $sql4;
        }
    } else {
        echo "<script>alert('No fields to update!')</script>";
    }
}
?>
