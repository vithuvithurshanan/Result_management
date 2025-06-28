<?php
session_start();
include("dbconnect.php");



if (isset($_GET['message']) && $_GET['message'] === 'loggedout') {
    echo '<p style="color: green; text-align: center; margin-top: 10px;">
            You have successfully logged out.
          </p>';
}


//  Developer Password Reset Tool (Temporary Use Only)
if (isset($_GET['reset']) && $_GET['reset'] == 1 && isset($_GET['student_id']) && isset($_GET['new'])) {
    $student_id = mysqli_real_escape_string($conn, $_GET['student_id']);
    $new_plain = $_GET['new'];
    $new_hashed = password_hash($new_plain, PASSWORD_DEFAULT);

    $update_sql = "UPDATE student SET password = '$new_hashed' WHERE student_id = '$student_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Password reset successful for $student_id. New password: $new_plain');</script>";
    } else {
        echo "<script>alert('Password reset failed: " . mysqli_error($conn) . "');</script>";
    }
}

// Handle Student Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_login'])) {
    $studentId = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = $_POST['password'];

    $query = "SELECT * FROM student WHERE student_id = '$studentId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $student = mysqli_fetch_assoc($result);
        if (password_verify($password, $student['password'])) {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['name'];
            header("Location: studentdash.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Student not found');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Result Management System</title>
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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

    <div class="login-selection">
        <h2>Select Login Type</h2>
        <button onclick="showAdminLogin()">Admin Login</button>
        <button onclick="showStudentLogin()">Student Login</button>
    </div>

    <!-- Admin Login Form -->
    <div id="adminLoginForm" class="login-form" style="display:none;">
        <span class="close-btn" onclick="closeForms()">&times;</span>
        <h2>Admin Login</h2>
        <form onsubmit="return validateAdminLogin()">
            <div class="input-container">
                <i class="fa fa-user"></i>
                <input type="text" id="adminUsername" placeholder="Username" required>
            </div>
            <div class="input-container">
                <i class="fa fa-lock"></i>
                <input type="password" id="adminPassword" placeholder="Password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
    </div>

    <!-- Student Login Form -->
    <div id="studentLoginForm" class="login-form" style="display:none;">
        <span class="close-btn" onclick="closeForms()">&times;</span>
        <h2>Student Login</h2>
        <form method="post" action="">
            <input type="hidden" name="student_login" value="1">
            <div class="input-container">
                <i class="fa fa-id-card"></i>
                <input type="text" name="student_id" placeholder="Student ID" required>
            </div>
            <div class="input-container">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        function showAdminLogin() {
            document.getElementById("adminLoginForm").style.display = "block";
            document.getElementById("studentLoginForm").style.display = "none";
        }

        function showStudentLogin() {
            document.getElementById("studentLoginForm").style.display = "block";
            document.getElementById("adminLoginForm").style.display = "none";
        }

        function closeForms() {
            document.getElementById("adminLoginForm").style.display = "none";
            document.getElementById("studentLoginForm").style.display = "none";
        }

        function validateAdminLogin() {
            let username = document.getElementById("adminUsername").value;
            let password = document.getElementById("adminPassword").value;

            if (username === "admin" && password === "1234") {
                alert("Admin Login Successful!");
                window.location.href = "admindash.php";
                return false;
            } else {
                alert("Invalid Admin Username or Password!");
                return false;
            }
        }
    </script>
</body>
</html>
