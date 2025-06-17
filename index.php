<?php
session_start(); 
require_once "nunchi_database.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nunchi</title>
    <link rel="stylesheet" href="style.css">
    <style>
    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap; 
    }

    li {
        flex: 0 1 calc(33.33% - 20px); 
        margin: 10px;
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

    .search-container {
        display: flex;
        align-items: center;
    }

    .search-container input[type="textbox"] {
        padding: 3px;
        font-size: 12px;
        border: 1px solid gray;
        border-radius: 5px;
    }

    .search-container button[type="submit"] {
        padding: 3px;
        font-size: 12px;
        border: 1px solid gray;
        border-radius: 5px;
        background-color: white;
        color: gray;
        cursor: pointer;
    }

    .search-container input[type="text"],
    .search-container button[type="submit"] {
        height: 20px;
    }

    .search {
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
        float: right;
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

</head>
<body>

<nav class="flex-div">
    <div class="nav-left flex-div">
        <img src="imagesWS/menu.png" class="menu-icon">
        <img src="imagesWS/logoWS.png" class="logo">
    </div>
    <div class="nav-middle flex-div">
        <img src="imagesWS/src.gif" class="src"></img>
        <div class="search-container flex-div">
    <form method="POST">
        <input type="textbox" name="search" id="submit" value="" />
        <button type="submit" name="submit" value="Search">Search</button>
    </form>
</div>

    </div>
    <div class="nav-right flex-div">


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
        <a href="view-channel.php?user_id=<?php echo $user_Id; ?>"><img src="<?php echo $profile; ?>"></a>
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
    <a href="login.php">Log in to your account</a>
    <img src="imagesWs/come.gif" width=60px; height=50px; float: right; margin-left:10px;></img>
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
    if(isset($_SESSION['auth'])){
        if(isset($_SESSION['payment_status']) && $_SESSION['payment_status'] == 'YES'){
            echo '<a href="upload.php"><img src="imagesWS/new-vid.gif" class="upload_video" style="width: 45px; height: 45px;">Upload</img></a>';
            echo '<a href="downloads.php"><img src="imagesWS/download.gif" class="upload_video" style="width: 45px; height: 45px;">Downloads</img></a>';
        } else{
            echo '<a href="pop.php?page=index"><img src="imagesWS/new-vid.gif" class="upload_video" style="width: 45px; height: 45px;">Upload</img></a>';
            echo '<a href="pop.php?page=index"><img src="imagesWS/download.gif" class="upload_video" style="width: 45px; height: 45px;">Downloads</img></a>';
        }
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

<!---------------------main-------------------->
<div class="container">
    <div class="banner">
    <?php if(!isset($_SESSION['auth'])){
        ?>
        <p>LOG IN AS ADMIN? <a href="loginadmin.php">ADMIN Login</a></p>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        <?php } else {
            ?>
            <a><img src="imagesWS/bunny.gif" style="width: 70px; height: 70px; border-radius: 15px;"><p> Thanks for being with Nunchi. We appreciate your contribution.</p></img></a>
            <?php
        }?>
    </div>

    <div class="list-container">
                <div class="video-list list-container">
                    <ul>
                    <?php
            
            require_once "nunchi_database.php";

            $searchPerformed = false;
            $searchResults = [];

            if (isset($_POST['submit'])) {
                $search = mysqli_real_escape_string($con, $_POST['search']);
                $searchPerformed = true;

                $query = "SELECT * FROM video 
                          WHERE title LIKE '%$search%' 
                          OR description LIKE '%$search%'
                          OR topic LIKE '%$search%'";

                $result = mysqli_query($con, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $searchResults[] = $row;
                    }
                } else {
                    echo 'There were no search results! Please make sure that all the words are spelled correctly.';
                }
            }
            ?>
                    <?php
                if (!$searchPerformed) {


$sql1 = "SELECT v_id, video, thumbnail, title, views, id FROM video ORDER BY views DESC"; 
$query1 = mysqli_query($con, $sql1);

$sql2 = "SELECT id, profile_picture, firstname, lastname FROM users";
$query2 = mysqli_query($con, $sql2);

$userProfilePictures = [];
$userFirstname =[];
$userLastname = [];
while ($user = mysqli_fetch_assoc($query2)) {
    $userProfilePictures[$user['id']] = $user['profile_picture'];
    $userFirstname[$user['id']] = $user['firstname'];
    $userLastname[$user['id']] = $user['lastname'];
}

while ($info = mysqli_fetch_assoc($query1)) {
    $userId = $info['id']; 
    $profilePicture = isset($userProfilePictures[$userId]) ? "profile_pictures/" . $userProfilePictures[$userId] : ''; // Check if profile picture exists
    $firstname = ($userFirstname[$userId]);
    $lastname = ($userLastname[$userId]);
    ?>
    <li>
        <a href="video-play.php?v_id=<?php echo $info['v_id']; ?>">
            <video width="300px" poster="thumbnail/<?php echo $info['thumbnail']; ?>"></video>
        </a>
        <p><?php echo $info['title']; ?></p>
        <p><?php echo $info['views']; ?> Views</p>
        <div>
            <a href="view-channel.php?user_id=<?php echo $info['id'] ?>"><img src="<?php echo $profilePicture; ?>" style="width: 30px; height: 30px; border-radius: 15px; float: left; margin-right: 10px; margin-bottom: 20px;"></a>
            <a><?php echo $firstname; ?> <?php echo $lastname; ?></a>
        </div>
    </li>
    <?php
}
} else {
    
    foreach ($searchResults as $row) {
        $userId = $row['id']; 
        ?>
        <li>
            <a href="video-play.php?v_id=<?php echo $row['v_id']; ?>">
                <video width="300px" poster="thumbnail/<?php echo $row['thumbnail']; ?>"></video>
            </a>
            <p><?php echo $row['title']; ?></p>
            <p><?php echo $row['views']; ?> Views </p>
        </li>
        <?php
    }
    } 
    ?>
        </ul>
        </div>
    </div>
    </div>
    
    <script src="script.js"></script>
    
    </body>
    </html>    