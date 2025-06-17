<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();
include_once "nunchi_database.php";

$email = "";
$message = "";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_POST['submit']) && !isset($_POST['verify_code'])) {
    unset($_SESSION['code_sent']);
    unset($_SESSION['reset_code']);
    unset($_SESSION['reset_email']);
    unset($_SESSION['reset_token']);
}

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } else {
        $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $code = rand(100000, 999999);
                $_SESSION['reset_code'] = $code;
                $_SESSION['reset_email'] = $email;

                $reset_token = md5(uniqid());
                $_SESSION['reset_token'] = $reset_token;

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'naymaaminnishy06@gmail.com'; 
                    $mail->Password = 'eqbtdefmapekccau'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('naymaaminnishy06@gmail.com', 'Nunchi-Admin');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Code';
                    $mail->Body    = "Your password reset code is: $code";

                    $mail->send();
                    $_SESSION['code_sent'] = true;
                } catch (Exception $e) {
                    $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $message = "Email not found";
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $con->error;
        }
    }
}

if (isset($_POST['verify_code'])) {
    $entered_code = trim($_POST['code']);
    if ($entered_code == $_SESSION['reset_code']) {
        header("Location: reset-password.php?token=" . $_SESSION['reset_token']);
        exit();
    } else {
        $message = "The entered code is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 100px 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            height: 400px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 15px;
            color: gray;
            text-align: center;
        }

        input[type="email"],
        input[type="text"] {
            width: 70%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid black;
            border-radius: 5px;
            font-size: 15px;
            color: black;
            text-align: center;
            float: right;
            margin-right: 50px;
        }

        button[type="submit"] {
            width: 75%;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            margin-top: 10px;
            margin-right: 50px;
            float: right;
        }

        button[type="submit"]:hover {
            background-color: gray;
        }

        p.error {
            color: #ff0000;
        }

        p.success {
            color: #008000;
        }

        img {
            width: 150px;
            height: 150px;
            align-self: center;
        }

        .fgt {
            margin-left: 100px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: white;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            color: gray;
            text-decoration: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <img src="imagesWS/src.gif" alt="Logo" class="fgt">
        <h2>Forgot Password</h2>
        <?php if (!isset($_SESSION['code_sent'])) : ?>
            <form method="post">
                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
                <button type="submit" name="submit">Submit</button>
            </form>
        <?php else : ?>
            <form method="post">
                <label>Enter the 6-digit code sent to your email:</label><br>
                <input type="text" name="code" required><br>
                <button type="submit" name="verify_code">Verify Code</button>
            </form>
        <?php endif; ?>
        <?php if (!empty($message)) : ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>