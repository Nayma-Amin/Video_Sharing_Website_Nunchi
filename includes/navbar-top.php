<?php
echo getcwd(); // For debugging: check the current working directory
session_start();
require_once 'nunchi_database.php'; // Adjust path as necessary

$profile = "default_profile.png"; // Default profile picture

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $sql = "SELECT profile FROM admins WHERE id = '$admin_id' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        if (!empty($admin['profile'])) {
            $profile = "admin_profiles/" . $admin['profile'];
            if (!file_exists($profile)) {
                $profile = "default_profile.png"; // Fallback to default if file does not exist
            }
        }
    }
}
?>


<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Nunchi</a>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo htmlspecialchars($profile); ?>" alt="Profile Picture" style="width: 30px; height: 30px; border-radius: 50%;">
            </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="terms.php">Terms</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                        <form action="logoutadmin.php" method="post" style="display: inline;">
                       <button type="submit" name="logoutadmin" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                    </ul>
                </li>
            </ul>
        </nav>