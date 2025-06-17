<?php
if (!isset($_GET['v_id']) || empty($_GET['v_id'])) {
    die('No video ID provided');
}
$vid_id = (int)$_GET['v_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Message - Nunchi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <iframe src="video-play.php?v_id=<?php echo $vid_id; ?>" id="backgroundVideo" frameborder="0"></iframe>
    <div class="overlay" id="overlay">
        <div class="popup">
            <h2>Please sign up to subscribe and enjoy other facilities</h2>
            <a href="signup.php"><button id="signupButtonp">Sign Up/Create Account</button></a>
            <a href="video-play.php?v_id=<?php echo $vid_id; ?>"><button id="closeButton">Continue without an account</button></a>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

