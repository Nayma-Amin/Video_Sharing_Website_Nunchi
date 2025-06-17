<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Nunchi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="flex-div">
        <div class="nav-left flex-div">
            <img src="imagesWS/logoWS.png" class="logo">
        </div>
        <div class="nav-middle flex-div">
        </div>
        <div class="nav-right flex-div">
        </div>
    </nav>
    <div class="container account-container">
        <img src="imagesWS/thankyou.gif"></img>
        <p>Please Read the Terms before Signing up...<a href="Terms.php">__Terms</a></p>
        <div class="sign">
            <h2>Sign Up</h2>
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="firstname" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="lastname" placeholder="Last Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="repeat_password" placeholder="Repeat Password" required>
                </div>
                <div class="form-group">
                    <p>Upload profile picture</p>
                    <input type="file" name="profile_picture" values="" placeholder="Select an image file" required><br><br>
                </div>
                <div class="form-group">
                    <button type="submit" class="sign-up-button" name="signUpButtonU" id="signUpButtonU">Sign Up as a Creator</button>
                </div>
                <div class="form-group">
                    <button type="submit" class="sign-up-button" name="signUpButtonN" id="signUpButtonN">Sign Up as a Normal User</button>
                </div>
            </form>
            <div id="signupMessage"></div>
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>
<?php
require_once "nunchi_database.php";

if (isset($_POST['signUpButtonU']) || isset($_POST['signUpButtonN'])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeat_password = $_POST["repeat_password"];
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_type = $_FILES['profile_picture']['type'];
    $profile_picture_size = $_FILES['profile_picture']['size'];
    $profile_picture_temp_loc = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_store = "profile_pictures/" . $profile_picture;

    if ($password !== $repeat_password) {
        echo "<script>alert('Passwords do not match!')</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    move_uploaded_file($profile_picture_temp_loc, $profile_picture_store);

    $check = "SELECT email FROM users WHERE email='$email'";
    $check_run = mysqli_query($con, $check);

    if (mysqli_num_rows($check_run) > 0) {
        echo "<script>alert('Email already exists!')</script>";
        exit();
    } else {
        $sql = "INSERT INTO users (firstname, lastname, email, password, profile_picture) 
                VALUES ('$firstname', '$lastname', '$email', '$hashed_password', '$profile_picture')";
        $query = mysqli_query($con, $sql);

        if ($query) {
            if (isset($_POST['signUpButtonU'])) {
                echo "<script>alert('Make payment request to complete process')</script>";
                header("Location: payment.php");
            } else if (isset($_POST['signUpButtonN'])) {
                echo "<script>alert('Registration successful. Redirecting to login page...')</script>";
                header("Location: login.php");
            }
            exit();
        }
    }
}
?>
