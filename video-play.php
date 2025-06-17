<?php
session_start();
require_once "nunchi_database.php"; 

function fetch_video_by_id($vid_id) {
    global $con;
    $query = "SELECT v.v_id, v.title, v.description, v.thumbnail, v.video, v.likes, v.dislikes, v.views, v.upload_date, 
                      v.topic, u.id, u.firstname, u.lastname, u.profile_picture
              FROM video v 
              JOIN users u ON v.id = u.id
              WHERE v.v_id = $vid_id";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $video = mysqli_fetch_assoc($result);
    return $video;
}

function fetch_related_videos($vid_id, $topic) {
    global $con;
    $query = "SELECT v.v_id, v.title, v.description, v.thumbnail, v.video, v.likes, v.dislikes, v.views, v.upload_date, 
                     u.firstname, u.lastname, u.profile_picture
              FROM video v 
              JOIN users u ON v.id = u.id
              WHERE v.v_id != $vid_id AND v.topic = '" . mysqli_real_escape_string($con, $topic) . "'
              ORDER BY RAND() 
              LIMIT 5";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $related_videos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $related_videos[] = $row;
    }

    return $related_videos;
}

function fetch_additional_videos($vid_id, $limit) {
    global $con;
    $query = "SELECT v.v_id, v.title, v.description, v.thumbnail, v.video, v.likes, v.dislikes, v.views, v.upload_date, 
                     u.firstname, u.lastname, u.profile_picture
              FROM video v 
              JOIN users u ON v.id = u.id
              WHERE v.v_id != $vid_id
              ORDER BY RAND() 
              LIMIT $limit";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Error: ' . mysqli_error($con)); 
    }

    $videos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $videos[] = $row;
    }

    return $videos;
}

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

if (isset($_GET['v_id'])) {
    $vid_id = (int)$_GET['v_id'];
    if ($vid_id <= 0) {
        die('Invalid video ID');
    }

    $video = fetch_video_by_id($vid_id);

    if (!$video) {
        die("No video found for ID: $vid_id");
    }

    $related_videos = fetch_related_videos($vid_id, $video['topic']);
    $additional_videos = fetch_additional_videos($vid_id, 9 - count($related_videos));
} else {
    die('No video ID provided');
}

$subscriptions = fetch_subscribed_users();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Playing Video - Nunchi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="flex-div">
        <div class="nav-left flex-div">
            <img src="imagesWS/menu.png" class="menu-iconV">
            <li><a href="index.php"><img src="imagesWS/logoWS.png" class="logo"></a></li>
        </div>
        <div class="nav-middle flex-div">
            <div class="search-box flex-div">
            </div>
        </div>
        <div class="nav-right flex-div">
            <style>
                label {
                    font-weight: bold;
                    max-width: 300px;
                    height: 30px;
                    margin-left: 6px;
                    cursor: pointer;

                }
                a {
                    text-decoration: none;
                    color: gray;
                    cursor: pointer;

                }
                
                li{
                   list-style-type: none;
                }
                .log{
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

            </style>

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
                <img src="imagesWs/come.gif" width=60px; height=60px; float: right; margin-left:10px;></img>
                    <a href="login.php">Log in to your account</a>
                </div>
                <?php
            }
            ?>
        </div>
    </nav>
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
            <a href="https://www.facebook.com/profile.php?id=61559870791224&mibextid=ZbWKwL"><img src="imagesWS/contact us.gif" style="width: 45px; height: 35px;"><p>Contact Us</p></a>
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
    <div class="container play-container">
        <div class="row">
            <div class="play-video">
                <?php if ($video): ?>
                    <video id="videoPlayer" controls autoplay controlsList="nodownload" data-videoid="<?php echo $video['v_id']; ?>">
    <source src="video/<?php echo htmlspecialchars($video['video']); ?>" type="video/mp4">
</video>

                    <div class="tags"></div>
                    <h3><?php echo htmlspecialchars($video['title']); ?></h3>
                    <div class="play-video-info">
                        <p>
                            <?php echo htmlspecialchars($video['views']); ?> Views &bull;
                            <?php echo htmlspecialchars($video['upload_date']); ?>
                        </p>                          
                        <style>
                            
                            .LD {
                                background-color: transparent;
                                border: none;
                                cursor: pointer;
                                max-width: 60px;
                                font-size: 15px;
                                display: block;
                                float: right;
                                margin-bottom: 10px;
                            }
                            .sub{
                                background-color: dodgerblue;
                                border: none;
                                cursor: pointer;
                                max-width: 300px;
                                font-size: 15px;
                                display: block;
                                margin-right: 15px;
                                margin-bottom: 10px;
                            }
                            
                #likeBtn {
                    margin-left: 250px;
                    color: green; 
                }

            
                #dislikeBtn {
                    margin-right: 10px;
                    color: red; 
                }
                #subscribeBtn {
                                margin-left: 400px;
                                float: right;
                }

                            #downloadBtn {
                                color: blue; 
                                margin-right: 10px;
                            }
                        </style>
             <button id="likeBtn" class="LD" data-videoid="<?php echo $video['v_id']; ?>" data-action="like">Like
            </button>
            <span id="likesCount"><?php echo htmlspecialchars($video['likes']); ?></span>

            <button id="dislikeBtn" class="LD" data-videoid="<?php echo $video['v_id']; ?>" data-action="dislike">Dislike
            </button>
            <span id="dislikesCount"><?php echo htmlspecialchars($video['dislikes']); ?></span>
                       <?php if (isset($_SESSION['auth']) && $_SESSION['payment_status'] === 'YES'): ?>
    <button id="downloadBtn" class="LD" data-videoid="<?php echo $video['v_id']; ?>">
        <i class="far fa-arrow-alt-circle-down"></i>
        <img src="imagesWS/download.gif" width="40px" height="30px">
    </button>
<?php else: ?>
    <a href="pop-up.php?v_id=<?php echo $video['v_id']; ?>"><button>Download</button></a>
<?php endif; ?>
                    </div>
                    <hr>
                    <div class="publisher">
                        <a href="view-channel.php?user_id=<?php echo $video['id']; ?>">
                        <img src="profile_pictures/<?php echo htmlspecialchars($video['profile_picture']); ?>" alt="Profile Picture">
                    </a>
                        <div>
                            <p><?php echo htmlspecialchars($video['firstname'] . ' ' . $video['lastname']); ?></p>
                        </div>
                        <?php
require_once "nunchi_database.php";

$logged_in = isset($_SESSION['auth']);
$current_user_id = $logged_in ? $_SESSION['user_id'] : null;
$payment_status = $logged_in ? $_SESSION['payment_status'] : null;

$uploader_id = $video['id'];

if ($current_user_id) {
    $subscribed_query = "SELECT * FROM subscriptions WHERE user_id = $current_user_id AND subscribed_to_user_id = $uploader_id";
    $subscribed_result = mysqli_query($con, $subscribed_query);
    $is_subscribed = (mysqli_num_rows($subscribed_result) > 0);
} else {
    $is_subscribed = false;
}

$subscribers_query = "SELECT subscribers FROM users WHERE id = $uploader_id";
$subscribers_result = mysqli_query($con, $subscribers_query);
$subscribers_row = mysqli_fetch_assoc($subscribers_result);
$subscriber_count = $subscribers_row['subscribers'];
?>

<button class="sub" id="subscribeBtn" data-userid="<?php echo htmlspecialchars($uploader_id); ?>" 
        data-loggedin="<?php echo json_encode($logged_in); ?>" 
        data-paymentstatus="<?php echo htmlspecialchars($payment_status); ?>"
        data-videoid="<?php echo htmlspecialchars($video['v_id']); ?>">
    <?php echo $is_subscribed ? "Unsubscribe" : "Subscribe"; ?>
</button>
<span id="subscribersCount"><?php echo htmlspecialchars($subscriber_count); ?></span>
                    </div>
                    <div class="video-description">
                        <p><?php echo htmlspecialchars($video['description']); ?></p>
                    </div>
                <?php else: ?>
                    <p>No video found.</p>
                <?php endif; ?>
            </div>
            <div class="right-sidebar">
                <?php if (!empty($related_videos) || !empty($additional_videos)): ?>
                    <?php foreach ($related_videos as $video): ?>
                        <li>
                            <div class="side-video-list">
                                <a href="video-play.php?v_id=<?php echo htmlspecialchars($video['v_id']); ?>" class="small-thumbnail">
                                    <img src="thumbnail/<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="Thumbnail">
                                </a>
                                <div class="video-info">
                                    <a href="video-play.php?v_id=<?php echo htmlspecialchars($video['v_id']); ?>"><?php echo htmlspecialchars($video['title']); ?></a>
                                    <p><?php echo htmlspecialchars($video['firstname'] . ' ' . $video['lastname']); ?></p>
                                    <p><?php echo htmlspecialchars($video['views']); ?> Views</p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>

                    <?php foreach ($additional_videos as $video): ?>
                        <li>
                            <div class="side-video-list">
                                <a href="video-play.php?v_id=<?php echo htmlspecialchars($video['v_id']); ?>" class="small-thumbnail">
                                    <img src="thumbnail/<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="Thumbnail">
                                </a>
                                <div class="video-info">
                                    <a href="video-play.php?v_id=<?php echo htmlspecialchars($video['v_id']); ?>"><?php echo htmlspecialchars($video['title']); ?></a>
                                    <p><?php echo htmlspecialchars($video['firstname'] . ' ' . $video['lastname']); ?></p>
                                    <p><?php echo htmlspecialchars($video['views']); ?> Views</p>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No recommended videos.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>       
    <script>
document.addEventListener("DOMContentLoaded", function() {
    const downloadBtn = document.getElementById("downloadBtn");
    if (downloadBtn) {
        downloadBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const videoId = this.getAttribute("data-videoid");

            fetch("download.php", {
                method: "POST", 
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ video_id: videoId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Download recorded successfully.");
                } else {
                    alert("Error recording download: " + data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }

    const videoPlayer = document.getElementById("videoPlayer");
let viewUpdated = false;
let playTime = 0;
let intervalId;

if (videoPlayer) {
    videoPlayer.addEventListener("play", function() {
        if (!viewUpdated) {
            intervalId = setInterval(() => {
                playTime += 1; 

                if (playTime >= 60) { 
                    const videoId = videoPlayer.getAttribute("data-videoid");

                    fetch("update_views.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ video_id: videoId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log("View updated successfully.");
                            viewUpdated = true;
                            clearInterval(intervalId); 

                            
                            const viewsElement = document.querySelector(".play-video-info p");
                            if (viewsElement) {
                                let currentViews = parseInt(viewsElement.innerText.split(" ")[0]);
                                viewsElement.innerText = `${currentViews + 1} Views • ${viewsElement.innerText.split(" • ")[1]}`;
                            }

                            
                            const sidebarVideos = document.querySelectorAll(".side-video-list");
                            sidebarVideos.forEach(sidebarVideo => {
                                const sidebarVideoId = sidebarVideo.querySelector("a").getAttribute("href").split("v_id=")[1];
                                if (sidebarVideoId == videoId) {
                                    const sidebarViewsElement = sidebarVideo.querySelector(".video-info p:last-child");
                                    let sidebarCurrentViews = parseInt(sidebarViewsElement.innerText.split(" ")[0]);
                                    sidebarViewsElement.innerText = `${sidebarCurrentViews + 1} Views`;
                                }
                            });
                        } else {
                            console.error("Error updating view: " + data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
                }
            }, 1000); 
        }
    });

    videoPlayer.addEventListener("pause", function() {
        clearInterval(intervalId); 
    });

    videoPlayer.addEventListener("ended", function() {
        clearInterval(intervalId); 
    });
}

    const likeBtn = document.getElementById("likeBtn");
    const dislikeBtn = document.getElementById("dislikeBtn");

    if (likeBtn && dislikeBtn) {
        likeBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const videoId = this.getAttribute("data-videoid");
            updateLikes(videoId, 'like');
        });

        dislikeBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const videoId = this.getAttribute("data-videoid");
            updateLikes(videoId, 'dislike');
        });
    }

    function updateLikes(videoId, action) {
        fetch("update_likes.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ video_id: videoId, action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("likesCount").innerText = data.likes;
                document.getElementById("dislikesCount").innerText = data.dislikes;
            } else {
                alert("Error updating likes/dislikes: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }
    });
    document.addEventListener("DOMContentLoaded", function() {
    const subscribeBtn = document.getElementById("subscribeBtn");

    if (subscribeBtn) {
        subscribeBtn.addEventListener("click", function(e) {
            e.preventDefault();
            const subscribedToUserId = this.getAttribute("data-userid");
            const loggedIn = this.getAttribute("data-loggedin") === 'true';
            const paymentStatus = this.getAttribute("data-paymentstatus");
            const videoId = this.getAttribute("data-videoid");

            if (!loggedIn) {
                window.location.href = `pop-up.php?v_id=${videoId}`;
                return;
            }

            if (paymentStatus !== 'YES') {
                window.location.href = `pop.php?page=video-play&v_id=${videoId}`;
                return;
            }
            updateSubscription(subscribedToUserId);
        });
    }

    function updateSubscription(subscribedToUserId) {
        fetch("update_subscription.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ subscribed_to_user_id: subscribedToUserId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("subscribersCount").innerText = data.subscribers;
                if (data.subscribed) {
                    subscribeBtn.innerText = "Unsubscribe";
                } else {
                    subscribeBtn.innerText = "Subscribe";
                }
            } else {
                alert("Error updating subscription: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }
});
</script>

</body>
</html>