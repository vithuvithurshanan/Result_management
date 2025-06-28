<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications | Coming Soon</title>
    <link rel="stylesheet" href="../CSS/studentdash.css">
    <link rel="stylesheet" href="../CSS/notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header>
    <img src="../Images/atilogo.jpg" alt="ATI Logo" class="logo">
    <h1>Result Management System</h1>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="contect.php">Contact</a></li>
        </ul>
    </nav>
</header>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Panel</h2>
        <ul>
             <li><a href="studentdash.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="studentview.php"><i class="fas fa-eye"></i> View Results</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li ><a href="noti.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="coming-soon-container">
            <img src="../Images/cooming.jpg" alt="Coming Soon">
            <h2>Coming Soon!</h2>
            <p>This feature is under development. </p>
        </div>
    </div>
</div>

</body>
</html>
