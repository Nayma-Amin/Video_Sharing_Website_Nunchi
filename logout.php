<?php
session_start();

if(isset($_POST['logout'])){
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);

    echo "<script>('Logged Out')</script>";
    header('Location: index.php');
    exit(0);
}

?>