<?php
require_once('nunchi_database.php');

if (isset($_POST['add_admin'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    $profile = $_FILES['profile_picture']['name'];
    $profile_type = $_FILES['profile_picture']['type'];
    $profile_size = $_FILES['profile_picture']['size'];
    $profile_temp_loc = $_FILES['profile_picture']['tmp_name'];
    $profile_store = "admin_profiles/" . $profile;

    if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $_FILES['profile_picture']['error']);
    }

    echo "Profile: " . $profile . "<br>";
    echo "Type: " . $profile_type . "<br>";
    echo "Size: " . $profile_size . "<br>";
    echo "Temp location: " . $profile_temp_loc . "<br>";
    echo "Store location: " . $profile_store . "<br>";

    if (move_uploaded_file($profile_temp_loc, $profile_store)) {
        echo "File successfully uploaded.";
    } else {
        echo "Failed to move uploaded file.";
    }

    $sql = "INSERT INTO admins (username, email, password, profile) VALUES ('$username', '$email', '$password', '$profile')";
    $query = mysqli_query($con, $sql);

    if ($query) {
        echo "<script>alert('Admin added successfully!')</script>";
        header("Location: admins.php");
        exit();
    } else {
        echo "<script>alert('Admin not added!')</script>";
        echo "SQL Error: " . mysqli_error($con);
    }
}
?>
