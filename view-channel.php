<?php
session_start();
require_once "nunchi_database.php";


$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null);
$logged_in_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


$videos = [];
if ($user_id) {
    $query = "SELECT v_id, title, thumbnail, upload_date, views FROM video WHERE id = ? ORDER BY views DESC";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $videos_result = $stmt->get_result();

    while ($row = $videos_result->fetch_assoc()) {
        $videos[] = $row;
    }
    $stmt->close();

    
    $sql1 = "SELECT id, profile_picture, firstname, lastname, email, password, subscribers FROM users WHERE id = ? LIMIT 1";
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $user_result = $stmt1->get_result();
    $user = $user_result->fetch_assoc();
    $stmt1->close();

}

function getProfileLink($user_id) {
    return "view-channel.php?id=$user_id";
}

if (isset($_GET['action']) && $_GET['action'] == 'get_video' && isset($_GET['v_id'])) {
    $video_id = intval($_GET['v_id']);
    
    $sql = "SELECT v_id, title, description, thumbnail FROM video WHERE v_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $video_result = $stmt->get_result();
    $video = $video_result->fetch_assoc();
    $stmt->close();

    
    echo json_encode($video);
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Channel - Video Sharing Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="flex-div">
    <div class="nav-left flex-div">
        <a href="index.php"><img src="imagesWS/logoWS.png" class="logo"></a>
    </div>
    <div class="nav-middle flex-div"></div>
    <div class="nav-right flex-div">
        <style>
            label {
                font-weight: bold;
                max-width: 300px;
                height: 30px;
                margin-left: 6px;
            }
            a {
                text-decoration: none;
                color: gray;
            }
            .logout {
                font-weight: bold;
                max-width: 300px;
                height: 30px;
                margin-left: 10px;
                margin-bottom: 30px;
                cursor: pointer;
            }
            .src {
                width: 50px;
                height: 50px;
                cursor: pointer;
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
            .user-profile {
    display: flex;
    align-items: center;
}

.user-profile img {
    border-radius: 50%;
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-right: 20px;
}

.user-profile button {
    padding: 10px 20px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.user-profile button:hover {
    background-color: #555;
}

.channel-name {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 100px;
}

ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap; 
    }

    li {
        flex: 1 1 calc(33.33% - 20px); 
        margin: 20px;
        box-sizing: border-box;
        cursor: pointer;
    }

.videos-list ul li a {
    text-decoration: none;
    color: black;
}

.videos-list ul li h3{
    font-size: 20px;
    font-weight: bold;
    float: left;
}

.video-view {
    color: gray;
    font-size: 15px;
    float: right;
    margin-right: 10px;
}

.videos-list {
    margin-top: 20px;
}

.videos-list h2 {
    margin-bottom: 20px;
    float: left;
}

.video-grid {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.video-item {
    flex: 1 1 calc(33.3333% - 20px);
    box-sizing: border-box;
    margin-bottom: 20px;
}

.video-thumbnail {
    position: relative;
}

.video-thumbnail img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.video-title {
    font-size: 20px;
    font-weight: bold;
    margin-top: 10px;
}

.video-view {
    color: gray;
    font-size: 15px;
    margin-top: 5px;
}

.video-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.edit-button {
    background-color: blue;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.delete-button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

#editModal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    padding-top: 60px;
}

#editForm {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
}

.container{
    float: left;
}

.update_channel{
    background-color: blue;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    width: 200px;
}

.update_channel:hover {
    background-color: darkblue;
}

.cancel-button{
    background-color: red;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    width: 150px;
    float: none;
}

.cancel-button:hover {
    background-color: #e53935; /* Red */
}

.edit_video {
    background-color: blue;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    width: 200px;
}

.edit_video:hover {
    background-color: darkblue;
}

.cancelEdit{
    background-color: red;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    width: 150px;
    float: none;
}

.cancelEdit:hover {
    background-color: #e53935; 
}

#deleteChannelModal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    padding-top: 60px;
}

#deleteChannelReasonForm{
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
}

.sub{
    background-color: gray;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    width: 150px;
    float: none;
}
.sub:hover {
    background-color: lightgray; 
}

#email-button{
    float: right;
}

        </style>

        <?php
        if (isset($_SESSION['auth'])) {
            
            $user_Id = $_SESSION['user_id'];
            $sql1 = "SELECT profile_picture, email, password FROM users WHERE id = '$user_Id' LIMIT 1";
            $query1 = mysqli_query($con, $sql1);

            if ($query1 && mysqli_num_rows($query1) > 0) {
                $user_logged_in = mysqli_fetch_assoc($query1);
                $profile = "profile_pictures/" . $user_logged_in['profile_picture'];
                $email = $user_logged_in['email'];
                $password = $user_logged_in['password'];
                ?>
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
                <img src="imagesWs/come.gif" width=100px; height=50px; float: right; margin-left: 10px;></img>
                <a href="login.php">Log in to your account</a>
            </div>
            <?php
        }
        ?>
    </div>
</nav>

<div class="">
    <div class="channel-header">
    <?php
if ($user_id && isset($user)) {
    $profile = "profile_pictures/" . $user['profile_picture'];
    ?>
    <?php
   if (isset($_SESSION['auth']) && $_SESSION['user_id'] != $user['id']): 
    $gmail_link = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($user['email']);
?>
    <h4><a href="<?php echo $gmail_link; ?>" target="_blank" class="email-button">Contact Creator Via Email</a></h4>
<?php endif; ?>
    <?php
$user_id = $_SESSION['user_id'] ?? null;
$user_exists = false;
if ($user_id) {
    $check_user_sql = "SELECT id FROM bins_user WHERE id = ?";
    $stmt = $con->prepare($check_user_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_exists = $result->num_rows > 0;
}
?>
<div class="user-profile">
    <form method="post" action="">
        <img src="<?php echo $profile; ?>" alt="Profile Picture">
        <?php if (isset($_SESSION['auth']) && $_SESSION['user_id'] == $user['id']): ?>
            <button type="submit" name="edit_channel">Edit Channel</button>
            <?php if (!$user_exists): ?>
                <button type="button" name="delete_channel">Delete Channel Request</button>
            <?php else: ?>
                <button class="cancelDeleteChannelButton" data-id="<?php echo $user_id; ?>">Cancel Delete Channel Request</button>
            <?php endif; ?>
        <?php endif; ?>
    </form>
    <h1 class="channel-name"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h1>
    <p><?php echo htmlspecialchars($user['subscribers']); ?> Subscribers</p>
</div>
<div id="deleteChannelModal" style="display:none;">
    <form id="deleteChannelReasonForm" method="POST">
        <label for="reason">Reason for Deleting Channel:</label>
        <input type="text" name="reason" id="reason" required>
        <button type="submit" name="delete_channel_confirm">Confirm Delete Channel</button>
        <button type="button" id="cancelDeleteChannel">Cancel</button>
    </form>
</div>

<?php

if (isset($_POST['edit_channel'])) {

    echo '<form method="post" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="firstname" value="' . htmlspecialchars($user['firstname']) . '"><br>
            <label>Last Name:</label>
            <input type="text" name="lastname" value="' . htmlspecialchars($user['lastname']) . '"><br>
            <label>Email:</label>
            <input type="email" name="email" value="' . htmlspecialchars($user['email']) . '"><br>
            <label>Current Password:</label>
            <input type="password" name="current_password" value="' . htmlspecialchars($user['password']) . '" readonly><br>
            <label>New Password:</label>
            <input type="password" name="new_password"><br>
            <label>Retype New Password:</label>
            <input type="password" name="retype_new_password"><br>
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture"><br>
            <button class="update_channel" type="submit" name="update_channel">Update Channel</button>
            <button class="cancel-button" type="submit" name="cancel">Cancel</button> <!-- Add Cancel button -->
          </form>';
}

if (isset($_POST['update_channel'])) {
    
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $current_password = htmlspecialchars($_POST['current_password']);
    $new_password = $_POST['new_password'];
    $retype_new_password = $_POST['retype_new_password'];
    $profile_picture = $user['profile_picture'];


    if (!empty($new_password) && $new_password === $retype_new_password) {
        $password = password_hash($new_password, PASSWORD_DEFAULT);
    } else {
        $password = $user['password']; 
    }

    
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "profile_pictures/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;
    }

    $stmt = $con->prepare("UPDATE users SET firstname=?, lastname=?, email=?, password=?, profile_picture=? WHERE id=?");
    $stmt->bind_param("sssssi", $firstname, $lastname, $email, $password, $profile_picture, $user['id']);
    if ($stmt->execute()) {
        echo "<script>
            alert('Channel updated successfully.');
            window.location.href = 'view-channel.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to update channel.');
        </script>";
    }
}

if (isset($_POST['cancel'])) {
    
    echo '<script>window.location.href = "view-channel.php";</script>';
    exit();
}

?>
<?php
} else {
    echo "Profile picture not found.";
}

        if (!empty($videos)) {
            ?>
        <div class="videos-list">
    <h2>Uploaded Videos</h2>
    <ul class="video-grid">
        <?php foreach ($videos as $video) { ?>
            <li class="video-item">
                <div class="video-thumbnail">
                    <a href="video-play.php?v_id=<?php echo $video['v_id']; ?>">
                        <img src="thumbnail/<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="Video Thumbnail">
                    </a>
                    <h3 class="video-title"><?php echo htmlspecialchars($video['title']); ?></h3>
                    <h4 class="video-view" ><?php echo htmlspecialchars($video['views']); ?><p>Views</p></h4>
        
                    <div class="video-actions">
    <?php if (isset($_SESSION['auth']) && $_SESSION['user_id'] == $user['id']): ?>
        <button class="edit-button" data-id="<?php echo $video['v_id']; ?>">Edit</button>
        <button class="delete-button" data-id="<?php echo $video['v_id']; ?>">Delete</button>
    <?php endif; ?>
</div>

                </div>
            </li>
        <?php } ?>
    </ul>
</div>


<div id="editModal" style="display:none;">
    <form id="editForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="video_id" id="video_id">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        <p></p>
        <label for="thumbnail">Thumbnail:</label>
        <input type="file" name="thumbnail" id="thumbnail">
        <button class="edit_video" type="submit" name="edit_video">Save Changes</button>
        <button class="cancelEdit" type="button" id="cancelEdit">Cancel</button> <!-- Added Cancel Button -->
    </form>
</div>


            <?php
        } else {
            echo "No videos uploaded yet.";
        }
        ?>

        <?php
require_once "nunchi_database.php";

if (isset($_POST['edit_video'])) {
    $videoId = $_POST['video_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $thumbnail = $_FILES['thumbnail']['name'];

    
    if ($thumbnail) {
        $targetDir = "thumbnail/";
        $targetFile = $targetDir . basename($thumbnail);
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetFile);

        
        $sql = "UPDATE video SET title = '$title', description = '$description', thumbnail = '$thumbnail' WHERE v_id = '$videoId'";
    } else {
        
        $sql = "UPDATE video SET title = '$title', description = '$description' WHERE v_id = '$videoId'";
    }

    if (mysqli_query($con, $sql)) {
        echo "<script>
            alert('Video edited successfully.');
            window.location.href = 'view-channel.php';
        </script>";
    } else {
        echo "<script>
            alert('Failed to edit video.');
        </script>";
    }
}
?>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    
    const editButtons = document.querySelectorAll('.edit-button');
    if (editButtons.length > 0) {
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var videoId = this.dataset.id;
                fetch('view-channel.php?action=get_video&v_id=' + videoId)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('video_id').value = data.v_id;
                        document.getElementById('title').value = data.title;
                        document.getElementById('description').value = data.description;
                        document.getElementById('editModal').style.display = 'block';
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    }

const cancelEditButton = document.getElementById('cancelEdit');
    if (cancelEditButton) {
        cancelEditButton.addEventListener('click', function() {
            document.getElementById('editModal').style.display = 'none';
        });
    }

   
    const deleteButtons = document.querySelectorAll('.delete-button');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const v_id = this.getAttribute('data-id');
                const videoElement = this.closest('.video-item');

                if (confirm('Are you sure you want to delete this video?')) {
                    fetch('delete_video.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `v_id=${v_id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            videoElement.remove();
                            alert('Video deleted successfully');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('An error occurred while processing your request. Please try again.');
                    });
                }
            });
        });
    }

    const cancelDeleteButtons = document.querySelectorAll('.cancelDeleteChannelButton');
if (cancelDeleteButtons.length > 0) {
    cancelDeleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');

            if (confirm('Are you sure you want to cancel the delete channel request?')) {
                fetch('cancel_delete_channel_request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `cancel_delete_channel=1&user_id=${userId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Cancel delete channel request successful.');
                        window.location.href = 'view-channel.php';
                    } else {
                        alert('Failed to cancel delete channel request: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your request. Please try again.');
                });
            }
        });
    });
}


    const cancelButton = document.querySelector('.cancel-button');
    if (cancelButton) {
        cancelButton.addEventListener('click', function(event) {
            event.preventDefault();
            window.location.href = "view-channel.php";
        });
    }

    const channelForm = document.getElementById('channelForm');
    if (channelForm) {
        channelForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('view-channel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes("successfully")) {
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    document.querySelectorAll('button[name="delete_channel"]').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete your channel?')) {
                document.getElementById("deleteChannelModal").style.display = "block";
            }
        });
    });

     document.getElementById("deleteChannelReasonForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var formData = new FormData(this);
    formData.append('delete_channel_confirm', 'true'); 

    console.log(...formData);

    fetch('delete_channel_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        if (data.includes("Delete Channel Request submitted successfully")) {
            alert('Delete Channel Request submitted successfully. Your channel will be deleted within 7 days unless you cancel the request.');
            window.location.href = 'view-channel.php';
        } else {
            alert(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request. Please try again.');
    });
});


    const cancelDeleteChannel = document.getElementById("cancelDeleteChannel");
    if (cancelDeleteChannel) {
        cancelDeleteChannel.addEventListener("click", function() {
            document.getElementById("deleteChannelModal").style.display = "none";
        });
    }
});
    </script>

</body>
</html>