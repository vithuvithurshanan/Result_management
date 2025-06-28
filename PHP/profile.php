<?php
session_start();
include("dbconnect.php");

// Get student ID from session
$student_id = $_SESSION['student_id'] ?? null;

if ($student_id) {
    // Secure the student_id
    $student_id_safe = mysqli_real_escape_string($conn, $student_id);

    // Fetch student info from 'student' table
    $query = "SELECT * FROM student WHERE student_id = '$student_id_safe'";
    $result = mysqli_query($conn, $query);

    $student = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
} else {
    $student = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link rel="stylesheet" href="../CSS/studentdash.css">
    <link rel="stylesheet" href="../CSS/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Header -->
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
            <li class="active"><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="noti.php"><i class="fas fa-bell"></i> Notifications</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Student Profile</h2>

        <?php if ($student): ?>
        <div class="profile-card">
            <div class="profile-left">
                <img src="../Images/p2.png" alt="Profile Picture" class="profile-pic">
            </div>
            <div class="profile-right">
                <p><strong><i class="fas fa-id-badge"></i> Student ID:</strong> <?= htmlspecialchars($student['student_id']) ?></p>
                <p><strong><i class="fas fa-user"></i> Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
                <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                <p><strong><i class="fas fa-phone"></i> Phone:</strong> <?= htmlspecialchars($student['phone']) ?></p>
                <p><strong><i class="fas fa-building"></i> Department:</strong> <?= htmlspecialchars($student['department']) ?></p>
                <p><strong><i class="fas fa-calendar"></i> Year:</strong> <?= htmlspecialchars($student['year']) ?></p>
            </div>
        </div>
        <?php else: ?>
            <p style="color: red;">Student not logged in or profile not found. <a href="login.php">Go to Login</a></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
