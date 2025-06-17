<?php
session_start();

if (isset($_POST['logoutadmin'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_auth']);

    header('Location: index.php');
    exit(0);
}
?>
