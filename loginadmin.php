<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Nunchi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="flex-div">
        <div class="nav-left flex-div">
        <a href="index.php"><img src="imagesWS/logoWS.png" class="logo"></a>
        </div>
        <div class="nav-middle flex-div">
        </div>
        <div class="nav-right flex-div">
        </div>
    </nav>
    <div class="container account-container">
    <ul>
    <?php
session_start();
require_once 'nunchi_database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql1 = "SELECT * FROM admins WHERE email = '$email' LIMIT 1";
    $query1 = mysqli_query($con, $sql1);

    if (mysqli_num_rows($query1) > 0) {
        $data = mysqli_fetch_assoc($query1);
        $stored_password = $data['password'];

        if ($password === $stored_password) { 
            $_SESSION['admin_id'] = $data['id'];
            $_SESSION['admin_email'] = $data['email'];
            $_SESSION['admin_auth'] = true;
            $_SESSION['admin_profile'] = $data['profile']; 

            header("Location: adminpanel.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Email not found.');</script>";
    }
}
?>
    </ul>
        <h2>ADMIN Login</h2>
        <div class="login-form">
            <form id="loginForm" action="loginadmin.php" method="POST">
                <input type="email" id="email" name="email" placeholder="Email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" class="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
