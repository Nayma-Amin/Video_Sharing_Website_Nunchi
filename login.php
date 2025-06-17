<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nunchi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="flex-div">
        <div class="nav-left flex-div">
          <img src= " imagesWS/logoWS.png" class="logo">
        </div>
       <div class="nav-middle flex-div">
       </div>
        <div class="nav-right flex-div">
   </nav>
    <div class="container account-container">
    <ul>
    <?php
session_start();
require_once "nunchi_database.php";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql2 = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $query2 = mysqli_query($con, $sql2);

    if(mysqli_num_rows($query2) > 0) {
        $data = mysqli_fetch_assoc($query2);
        $hashed_password = $data['password'];

        
        if(password_verify($password, $hashed_password)) {
            $user_id = $data['id'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $email = $data['email'];
            $profile_picture  = $data['profile_picture'];
            $payment_status = $data['payment_status'];


            $_SESSION['payment_status'] = $payment_status;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] =[
                'profile_picture' => $profile_picture,
            ];

            if($_SESSION['auth'] == true){
                header('location: index.php');
            } else {
                header('location: login.php');
            }
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>

    </ul>
    <img src="imagesWS/hi.gif"></img>
<h2>Login</h2>
        <div class="login-form">
        <form id="loginForm" action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-button" name ="login" >Login</button>
        </form>
        <div id="loginMessage"></div>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        <p>Forgot your password? <a href="forgot_password.php">Reset it</a></p>
    </div>
    </div>
    <script src="script.js"></script>
</body>
</html>