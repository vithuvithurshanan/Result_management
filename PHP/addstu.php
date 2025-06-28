<?php
include 'dbconnect.php';

$message = "";
$message_class = "";

// Define departments
$departments = ['IT', 'ENGLISH', ];

// Manual Add Student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['manual_add'])) {
    $student_id = trim($_POST['student_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $batch = trim($_POST['batch'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($student_id) || empty($name) || empty($department) || empty($email) || empty($password) || empty($batch)) {
        $message = "❌ All fields marked * are required!";
        $message_class = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "❌ Invalid email format!";
        $message_class = "error";
    } else {
        $check = $conn->prepare("SELECT student_id FROM student WHERE student_id = ?");
        $check->bind_param("s", $student_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "❌ Student ID already exists!";
            $message_class = "error";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO student (student_id, name, department, batch, email, password, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $student_id, $name, $department, $batch, $email, $hashed_password, $phone);
            if ($stmt->execute()) {
                $message = "✅ Student added successfully!";
                $message_class = "success";
            } else {
                $message = "❌ Error inserting student!";
                $message_class = "error";
            }
            $stmt->close();
        }
        $check->close();
    }
}

// CSV Bulk Add Students
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csv_add']) && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];
    $fileType = mime_content_type($file);

    if ($_FILES["csv_file"]["size"] > 0 && ($fileType === 'text/plain' || $fileType === 'text/csv')) {
        $handle = fopen($file, "r");
        $header = fgetcsv($handle);

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $student_id = trim($data[0] ?? '');
            $name = trim($data[1] ?? '');
            $department = trim($data[2] ?? '');
            $batch = trim($data[3] ?? '');
            $email = trim($data[4] ?? '');
            $password = trim($data[5] ?? '');
            $phone = trim($data[6] ?? '');

            if (!empty($student_id) && !empty($name) && !empty($department) && !empty($email) && !empty($password) && !empty($batch)) {
                $check = $conn->prepare("SELECT student_id FROM student WHERE student_id = ?");
                $check->bind_param("s", $student_id);
                $check->execute();
                $check->store_result();

                if ($check->num_rows == 0) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO student (student_id, name, department, batch, email, password, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $student_id, $name, $department, $batch, $email, $hashed_password, $phone);
                    $stmt->execute();
                    $stmt->close();
                }
                $check->close();
            }
        }
        fclose($handle);
        $message = "✅ CSV File Imported Successfully!";
        $message_class = "success";
    } else {
        $message = "❌ Please upload a valid CSV file!";
        $message_class = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student (Manual & CSV)</title>
    <link rel="stylesheet" href="../CSS/addstudent.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <div class="form-container">
        <h2>Add New Student (Manual)</h2>

        <?php if ($message): ?>
            <div class="message <?= $message_class ?>"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="manual_add" value="1">

            <label for="student_id">Student ID *</label>
            <input type="text" name="student_id" required>

            <label for="name">Name *</label>
            <input type="text" name="name" required>

            <label for="department">Department *</label>
            <select name="department" required>
                <option value="">Select Department</option>
                <?php foreach ($departments as $dept): ?>
                    <option value="<?= $dept ?>"><?= $dept ?></option>
                <?php endforeach; ?>
            </select>

            <label for="batch">Batch *</label>
            <input type="text" name="batch" required placeholder="e.g., 2023">

            <label for="email">Email *</label>
            <input type="email" name="email" required>

            <label for="password">Password *</label>
            <input type="password" name="password" required>

            <label for="phone">Phone</label>
            <input type="text" name="phone">

            <button type="submit">Add Student</button>
        </form>

        <hr><br>

        <h2>Bulk Add Students (CSV Upload)</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csv_add" value="1">

            <label for="csv_file">Select CSV File *</label>
            <input type="file" name="csv_file" accept=".csv" required>

            <button type="submit">Upload CSV</button>
        </form>

        <p class="csv-note"><strong>Note:</strong> CSV format should be:<br>
            <code>student_id, name, department, batch, email, password, phone</code>
        </p>

        <a href="viewstu.php" class="back-btn">← Back to View Students</a>
    </div>
</div>

</body>
</html>