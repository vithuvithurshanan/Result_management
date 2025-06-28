<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../CSS/admindash.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
</head>
<body>
    <header>
        <img src="../Images/atilogo.jpg" alt="ATI Logo" class="logo" />
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
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashbord.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="addstu.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
                <li><a href="addresult.php"><i class="fas fa-file-alt"></i> Add Result</a></li>
                <li><a href="updateresult.php"><i class="fas fa-edit"></i> Update Result</a></li>
                <li><a href="viewresult.php"><i class="fas fa-eye"></i> View Results</a></li>
                <li><a href="viewstu.php"><i class="fas fa-users"></i> View Students</a></li>
                <li><a href="logout.php" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

            </ul>
        </div>

        <div class="content">
            <h1>Welcome to Admin Dashboard</h1>
            <p>Manage students, results, and more.</p>

            <!-- Motivation Slider -->
            <div class="motivation-slider">
              <div class="slide active">
                <img src="../Images/a2.jpeg" alt="Motivation 1" />
                <div class="overlay">
                  <p>"The only way to do great work is to love what you do." – Steve Jobs</p>
                </div>
              </div>
              <div class="slide">
                <img src="../Images/a1.jpeg" />
                <div class="overlay">
                  <p>"Success usually comes to those who are too busy to be looking for it." – Henry David Thoreau</p>
                </div>
              </div>
              <div class="slide">
                <img src="../Images/a3.jpeg" alt="Motivation 3" />
                <div class="overlay">
                  <p>"Don’t watch the clock; do what it does. Keep going." – Sam Levenson</p>
                </div>
              </div>
            </div>
        </div>
    </div>

    <script>
      const slides = document.querySelectorAll('.motivation-slider .slide');
      let currentIndex = 0;

      function showSlide(index) {
        slides.forEach((slide, i) => {
          slide.classList.toggle('active', i === index);
        });
      }

      setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
      }, 5000); // Change slide every 5 seconds
    </script>
</body>
</html>
