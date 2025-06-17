<?php
if (!isset($_GET['page']) || empty($_GET['page'])) {
    die('No page provided');
}

$page = $_GET['page'];

if ($page !== 'index' && $page !== 'video-play') {
    die('Invalid page');
}

$vid_id = isset($_GET['v_id']) ? (int)$_GET['v_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Message - Nunchi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .background img,
        .background iframe {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .popup {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .popup h2 {
            margin-bottom: 20px;
        }
        .popup button {
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="background" id="background">
        <?php if ($page === 'index') : ?>
            <img src="index.php" alt="">
        <?php elseif ($page === 'video-play' && !is_null($vid_id)) : ?>
            <iframe src="video-play.php?v_id=<?php echo $vid_id; ?>" frameborder="0"></iframe>
        <?php endif; ?>
    </div>

    <div class="overlay" id="overlay">
        <div class="popup">
            <h2>Please complete the payment process to enjoy other facilities</h2>
            <a href="payment.php"><button id="paymentButton">Complete payment process</button></a>
            <a href="<?php echo $page === 'index' ? 'index.php' : 'video-play.php?v_id=' . $vid_id; ?>"><button id="continueButton">Continue as a normal user</button></a>
        </div>
    </div>
</body>
</html>
