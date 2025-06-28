<?php
include 'dbconnect.php';

$message = '';
$resultData = null;
$searched_id = '';
$studentInfo = null;
$allowedGrades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'F', 'A/B'];

// Handle update
if (isset($_POST['update'])) {
    $result_id = $_POST['result_id'];
    $grade = $_POST['grade'];

    // Backend validation
    if (!in_array($grade, $allowedGrades)) {
        $message = "Invalid grade selected.";
    } else {
        $updateSql = "UPDATE results SET grade = ? WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $grade, $result_id);

        if ($stmt->execute()) {
            $message = "Result updated successfully!";
        } else {
            $message = "Update failed: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch results and student info
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $searched_id = $student_id;

    $infoQuery = "SELECT name, department FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($infoQuery);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $studentResult = $stmt->get_result();
    $studentInfo = $studentResult->fetch_assoc();
    $stmt->close();

    $query = "
        SELECT results.id AS result_id, subjects.subject_code, subjects.subject_name, results.grade
        FROM results
        JOIN subjects ON results.subject_id = subjects.id
        WHERE results.student_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resultData = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Result</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/adminstyle.css">
    <link rel="stylesheet" href="../CSS/updateresult.css">
    <style>
        /* Success message fade-in and fade-out */
        .message.success {
            animation: fadeInOut 4s ease forwards;
        }
        @keyframes fadeInOut {
            0% {opacity: 0;}
            10% {opacity: 1;}
            90% {opacity: 1;}
            100% {opacity: 0;}
        }
    </style>
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

    <main class="content">
        <div class="main-content">
            <h2>Update Student Result</h2>

            <!-- Always show this search form -->
            <form method="GET" action="updateresult.php" class="search-form">
                <label for="student_id">Enter Student ID:</label>
                <input type="text" id="student_id" name="student_id" value="<?= htmlspecialchars($searched_id) ?>" required>
                <button type="submit">Search</button>
            </form>

            <!-- Show message -->
            <?php if (!empty($message)): ?>
                <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($searched_id && $studentInfo): ?>
                <div class="student-info">
                    <p><strong>Student Name:</strong> <?= htmlspecialchars($studentInfo['name']) ?></p>
                    <p><strong>Department:</strong> <?= htmlspecialchars($studentInfo['department']) ?></p>
                </div>
            <?php endif; ?>

            <!-- Show result update forms only after search -->
            <?php if ($searched_id && $resultData): ?>
                <?php foreach ($resultData as $row): ?>
                    <form method="POST" class="form-container" onsubmit="return validateGrade(this)">
                        <input type="hidden" name="result_id" value="<?= $row['result_id'] ?>">
                        <p><strong>Subject:</strong> <?= htmlspecialchars($row['subject_code']) ?> - <?= htmlspecialchars($row['subject_name']) ?></p>
                        <label for="grade_<?= $row['result_id'] ?>">Grade:</label>
                        <select name="grade" id="grade_<?= $row['result_id'] ?>" required>
                            <?php foreach ($allowedGrades as $g): ?>
                                <option value="<?= $g ?>" <?= $row['grade'] == $g ? 'selected' : '' ?>><?= $g ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update">Update</button>
                    </form>
                    <hr>
                <?php endforeach; ?>
            <?php elseif ($searched_id && !$resultData): ?>
                <p>No results found for student ID: <strong><?= htmlspecialchars($searched_id) ?></strong></p>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    // Frontend validation for grade select on form submit
    function validateGrade(form) {
        const select = form.querySelector('select[name="grade"]');
        const allowedGrades = <?= json_encode($allowedGrades); ?>;
        if (!allowedGrades.includes(select.value)) {
            alert("Invalid grade selected. Please choose a valid grade.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
