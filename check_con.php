<?php

define('DB_NAME', 'Database_Nunchi');
define('DB_USER', 'root');
define('DB_PASS', '456789');
define('DB_HOST', 'localhost');

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (mysqli_connect_errno()) {
    die("Failed to connect to database: " . mysqli_connect_error());
}
?>