<?php
session_start();
require_once "nunchi_database.php";

if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("
    SELECT v.video, v.title, v.thumbnail, v.v_id, u.id AS user_id, u.firstname, u.lastname, u.profile_picture 
    FROM downloads d 
    JOIN video v ON d.video_id = v.v_id 
    JOIN users u ON v.id = u.id 
    WHERE d.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$downloads = $result->fetch_all(MYSQLI_ASSOC);
?>
<?php
require_once "nunchi_database.php";

if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['v_id'])) {
    $video_id = intval($_GET['v_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $con->prepare("DELETE FROM downloads WHERE video_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $video_id, $user_id);
    $stmt->execute();

    header('Location: downloads.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Downloads - Nunchi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="flex-div">
    <div class="nav-left flex-div">
        <img src="imagesWS/menu.png" class="menu-icon">
        <a href="index.php"><img src="imagesWS/logoWS.png" class="logo"></a>
    </div>
    <div class="nav-middle flex-div">
        </div>
    </div>
    <div class="nav-right flex-div">
    <style>
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }
        li {
            flex: 0 1 calc(33.33% - 30px);
            margin: 15px;
            box-sizing: border-box; 
            cursor: pointer;
        }
        p {
            font-weight: bold;
            max-width: 300px;
            height: 30px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .nav-right {
            display: flex;
            align-items: center;
        }
        .nav-right img, .nav-right .logout {
            margin-left: 10px;
        }
        .log {
            margin-top: 10px;
            margin-left: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 10px;
            background-color: white;
            border-radius: 10px;
            border: 1px solid gray;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            color: gray;
            text-decoration: none;
            text-align: center;
        }
        .src {
            width: 50px;
            height: 50px;
            cursor: pointer;
        }
        .sidebar {
            float: left;
        }
        .container {
            overflow: hidden; 
        }
        .list-container {
            display: flex;
            flex-wrap: wrap;
        }
        .video-list {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
    <?php
    require_once "nunchi_database.php";

    function fetch_subscribed_users() {
        global $con;
        $query = "SELECT id, firstname, lastname, profile_picture FROM users LIMIT 5";
        $result = mysqli_query($con, $query);

        $subscribed_users = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $subscribed_users[] = $row;
            }
        }

        return $subscribed_users;
    }

    $subscriptions = fetch_subscribed_users();
    ?>
    <?php
    if (isset($_SESSION['auth'])) {
        $user_Id = $_SESSION['user_id'];
        $sql1 = "SELECT profile_picture FROM users WHERE id = '$user_Id' LIMIT 1";
        $query1 = mysqli_query($con, $sql1);

        if ($query1 && mysqli_num_rows($query1) > 0) {
            $user = mysqli_fetch_assoc($query1);
            $profile = "profile_pictures/" . $user['profile_picture'];
            ?>

            <div class="nav-right user-icon">
                <a href="view-channel.php"><img src="<?php echo $profile; ?>"></a>
            </div>
            <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit" name="logout" class="log">Log out</button>
                </form>
            </div>

            <?php
        } else {
            echo "Profile picture not found.";
        }
    } else {
        ?>
        <div class="login">
            <img src="imagesWS/come.gif" width=100px; height=100px; float: right; margin-left:10px;></img>
            <a href="login.php">Log in to your account</a>
        </div>
        <?php
    }
    ?>

    </div>
</nav>

<!---------------------Sidebar-------------------->
<div class="sidebar">
    <div class="shortcut-links">
        <a href="index.php"><img src="imagesWS/welcome-home.gif" style="width: 50px; height: 50px; border-radius: 15px;"><p>Home</p></a>
        <a href="terms.php"><img src="imagesWS/set.gif" style="width: 45px; height: 45px; border-radius: 15px;"><p>Terms</p></a>
        <?php 
        if (isset($_SESSION['auth'])) {
            echo '<a href="upload.php"><img src="imagesWS/new-vid.gif" class="upload_video" style="width: 45px; height: 45px;">Upload</img></a>';
        }
        ?>
        <a href="https://www.facebook.com/profile.php?id=61559870791224&mibextid=ZbWKwL">
            <img src="imagesWS/contact us.gif" style="width: 45px; height: 35px;"><p>Contact Us</p></a>

        <hr>
    </div>
    <div class="subscribed-list">
        <h3>USERS</h3>
        <?php foreach ($subscriptions as $user): ?>
    <?php 
    $profile_picture_path = 'profile_pictures/' . urlencode($user['profile_picture']); 
    ?>
    <a href="view-channel.php?user_id=<?php echo $user['id']; ?>">
        <img src="<?php echo htmlspecialchars($profile_picture_path); ?>" alt="Profile Picture">
        <p><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></p>
    </a>
<?php endforeach; ?>
    </div>
</div>
<div class="container">
    <h2>My Downloads</h2>
    <ul>
        <?php if ($downloads): ?>
            <?php foreach ($downloads as $download): ?>
                <li>
                    <a href="video-play.php?v_id=<?php echo $download['v_id']; ?>">
                        <?php
                
                        $thumbnail_path = "thumbnail/" . htmlspecialchars($download['thumbnail']);
                        ?>
                        <img src="<?php echo $thumbnail_path; ?>" width="330">
                        <?php echo htmlspecialchars($download['title']); ?>
                    </a>
                    <br>
                    <a href="view-channel.php?user_id=<?php echo $download['user_id']; ?>">
                        <img src="profile_pictures/<?php echo htmlspecialchars($download['profile_picture']); ?>" width="30" style="border-radius:15px;">
                        <?php echo htmlspecialchars($download['firstname'] . ' ' . $download['lastname']); ?>
                    </a>
                    <button class="delete-button" onclick="confirmDelete(<?php echo $download['v_id']; ?>)">Delete</button>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No downloads found.</p>
        <?php endif; ?>
    </ul>
</div>
<script>
        function confirmDelete(v_id) {
            if (confirm("Are you sure you want to delete this video?")) {
                window.location.href = 'downloads.php?v_id=' + v_id;
            }
        }
    </script>
</body>
</html>
