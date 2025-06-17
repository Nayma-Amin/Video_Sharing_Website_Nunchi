<?php
session_start();
include_once "nunchi_database.php";

$new_password = "";
$retype_password = "";
$message = "";

$reset_token = isset($_GET['token']) ? $_GET['token'] : '';
if (!isset($_SESSION['reset_token']) || $_SESSION['reset_token'] !== $reset_token) {
    header("Location: forgot_password.php");
    exit();
}


if (isset($_POST['submit'])) {

    $new_password = trim($_POST['new_password']);
    $retype_password = trim($_POST['retype_password']);

   
    if (empty($new_password) || empty($retype_password)) {
        $message = "Please enter both new password and retype password";
    } elseif ($new_password !== $retype_password) {
        $message = "Passwords do not match";
    } else {
        
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        
        $email = $_SESSION['reset_email'];
        $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("ss", $hashed_password, $email);
            if ($stmt->execute()) {
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_email']);
                $message = "Password changed successfully. You can now <a href='login.php'>login</a> with your new password.";
            } else {
                $message = "Failed to update password";
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            height: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        p.error {
            color: #ff0000;
        }

        p.success {
            color: #008000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="post">
            <label>New Password:</label><br>
            <input type="password" name="new_password"><br>
            <label>Retype Password:</label><br>
            <input type="password" name="retype_password"><br>
            <button type="submit" name="submit">Change Password</button>
        </form>
        <?php if (!empty($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
