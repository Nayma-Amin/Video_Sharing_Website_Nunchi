<?php
session_start();
require_once 'nunchi_database.php';

if (isset($_POST['delete_admin'])) {
    $admin_id = mysqli_real_escape_string($con, $_POST['admin_id']);

    if ($admin_id == $_SESSION['admin_id']) {
        echo "<script>alert('You cannot delete the currently logged-in admin.');</script>";
        header("Location: admins.php");
        exit();
    }

    $sql = "DELETE FROM admins WHERE id = '$admin_id'";
    $query = mysqli_query($con, $sql);

    if ($query) {
        echo "<script>alert('Admin deleted successfully.');</script>";
    } else {
        echo "<script>alert('Failed to delete admin.');</script>";
    }

    header("Location: admins.php");
    exit();
} else {
    header("Location: admins.php");
    exit();
}
?>
