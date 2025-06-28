<?php
session_start();

// Set session manually after JS login (temporary solution)
if (!isset($_SESSION['student_id'])) {
    $_SESSION['student_id'] = 'man/it/2022/f/30'; // Replace with the correct student ID
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../CSS/studentdash.css">
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
                <li><a href="studentview.php"><i class="fas fa-eye"></i> View Results</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="noti.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Welcome, <?= $_SESSION['student_id'] ?></h2>

            <!-- Motivation Slider -->
            <div class="motivation-slider">
                <div class="slide active">
                    <img src="../Images/st4.jpg" alt="Motivation 1" />
                    <div class="overlay">
                        <p>"The only way to do great work is to love what you do." – Steve Jobs</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="../Images/S4.jpg" alt="Motivation 2" />
                    <div class="overlay">
                        <p>"Success usually comes to those who are too busy to be looking for it." – Henry David Thoreau</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="../Images/st3.jpeg" alt="Motivation 3" />
                    <div class="overlay">
                        <p>"Don’t watch the clock; do what it does. Keep going." – Sam Levenson</p>
                    </div>
                </div>
            </div>
            <!-- End Motivation Slider -->
        </div>
    </div>

    <!-- JavaScript for Motivation Slider -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            setInterval(nextSlide, 3000);
        });
    </script>
</body>
</html>
