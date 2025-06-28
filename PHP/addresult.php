<?php
include("dbconnect.php");

$message = "";

// Valid grades array for validation
$valid_grades = ["A+", "A", "A-", "B+", "B", "B-", "C+", "C", "C-", "F", "A/B"];

// Handle AJAX request for subject list
if (isset($_POST['action']) && $_POST['action'] == 'get_subjects') {
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $course_group = mysqli_real_escape_string($conn, $_POST['course_group']);

    $query = "SELECT subject_code, subject_name FROM subjects 
              WHERE year='$year' AND semester='$semester' AND course_group='$course_group'";
    $result = mysqli_query($conn, $query);

    $options = "<option value=''>Select Subject</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . $row['subject_code'] . "'>" . $row['subject_name'] . "</option>";
    }
    echo $options;
    exit();
}

// Handle manual form submission
if (isset($_POST['submit_manual'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $subjectCode = mysqli_real_escape_string($conn, $_POST['subject']);
    $result_value = mysqli_real_escape_string($conn, $_POST['result']);
    $year = mysqli_real_escape_string($conn, $_POST['filter_year']);
    $semester = mysqli_real_escape_string($conn, $_POST['filter_semester']);

    // Validate grade value
    if (!in_array($result_value, $valid_grades)) {
        $message = "Invalid grade selected.";
    } else {
        // Check if student exists
        $checkStudent = "SELECT * FROM student WHERE student_id = '$student_id'";
        $studentResult = mysqli_query($conn, $checkStudent);

        if (mysqli_num_rows($studentResult) > 0) {
            // Get subject id from subjects table
            $getSubjectIdQuery = "SELECT id FROM subjects WHERE subject_code = '$subjectCode'";
            $subjectResult = mysqli_query($conn, $getSubjectIdQuery);

            if ($subjectRow = mysqli_fetch_assoc($subjectResult)) {
                $subject_id = $subjectRow['id'];

                // Insert result
                $insert = "INSERT INTO results (student_id, subject_id, grade, year, semester) 
                           VALUES ('$student_id', '$subject_id', '$result_value', '$year', '$semester')";
                if (mysqli_query($conn, $insert)) {
                    if ($result_value == "F") {
                        $message = "Result added successfully for Student ID: $student_id (Fail)";
                    } elseif ($result_value == "A/B") {
                        $message = "Result added successfully for Student ID: $student_id (Absent)";
                    } else {
                        $message = "Result added successfully for Student ID: $student_id";
                    }
                } else {
                    $message = "Error adding result: " . mysqli_error($conn);
                }
            } else {
                $message = "Selected subject does not exist in the database.";
            }
        } else {
            $message = "Student ID $student_id does not exist in the database.";
        }
    }
}

// Handle CSV file upload
if (isset($_POST['submit_csv'])) {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');
        fgetcsv($csvFile); // skip header row

        while (($line = fgetcsv($csvFile)) !== false) {
            $student_id = mysqli_real_escape_string($conn, $line[0]);
            $subjectCode = mysqli_real_escape_string($conn, $line[1]);
            $result_value = mysqli_real_escape_string($conn, $line[2]);
            $year = mysqli_real_escape_string($conn, $line[3]);
            $semester = mysqli_real_escape_string($conn, $line[4]);

            // Validate grade before insert
            if (!in_array($result_value, $valid_grades)) {
                continue; // Skip invalid grade rows
            }

            // Check if student exists
            $checkStudent = "SELECT * FROM student WHERE student_id = '$student_id'";
            $studentResult = mysqli_query($conn, $checkStudent);

            if (mysqli_num_rows($studentResult) > 0) {
                // Get subject id from subjects table
                $getSubjectIdQuery = "SELECT id FROM subjects WHERE subject_code = '$subjectCode'";
                $subjectResult = mysqli_query($conn, $getSubjectIdQuery);

                if ($subjectRow = mysqli_fetch_assoc($subjectResult)) {
                    $subject_id = $subjectRow['id'];

                    // Insert result
                    $insert = "INSERT INTO results (student_id, subject_id, grade, year, semester) 
                               VALUES ('$student_id', '$subject_id', '$result_value', '$year', '$semester')";
                    mysqli_query($conn, $insert);
                }
            }
        }
        fclose($csvFile);
        $message = "CSV file uploaded and results added successfully.";
    } else {
        $message = "Error uploading CSV file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Result (Manual & CSV Bulk)</title>
    <link rel="stylesheet" href="../CSS/adminstyle.css" />
    <link rel="stylesheet" href="../CSS/addresult.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
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

<div class="main-wrapper">
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
            <div class="container">
                <h2>Add Student Result </h2>

                <?php if (!empty($message)) : ?>
                    <p class="message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <div class="form-section">
                    <h3>Manual Result Add</h3>
                    <form method="POST" action="">
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" required />

                        <label for="filter_year">Year:</label>
                        <select id="filter_year" name="filter_year" required>
                            <option value="">Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                        </select>

                        <label for="filter_semester">Semester:</label>
                        <select id="filter_semester" name="filter_semester" required>
                            <option value="">Select Semester</option>
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                        </select>

                        <label for="filter_course_group">Course Group:</label>
                        <select id="filter_course_group" name="filter_course_group" required>
                            <option value="">Select Department</option>
                            <option value="IT">IT</option>
                            <option value="ENGLISH">ENGLISH</option>
                        </select>

                        <label for="subject">Subject:</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select Subject</option>
                        </select>

                        <label for="result">Result:</label>
                        <select id="result" name="result" required>
                            <option value="">Select Grade</option>
                            <option value="A+">A+</option>
                            <option value="A">A</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B">B</option>
                            <option value="B-">B-</option>
                            <option value="C+">C+</option>
                            <option value="C">C</option>
                            <option value="C-">C-</option>
                            <option value="F">F (Fail)</option>
                            <option value="A/B">A/B (Absent)</option>
                        </select>

                        <button type="submit" name="submit_manual" class="submit-btn">Add Result</button>
                    </form>
                </div>

                <div class="form-section">
                    <h3>CSV Bulk Upload</h3>
                    <form method="POST" enctype="multipart/form-data" action="">
                        <label for="csv_file">Upload CSV File:</label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv" required />
                        <button type="submit" name="submit_csv" class="submit-btn">Upload CSV</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const yearSelect = document.getElementById("filter_year");
    const semesterSelect = document.getElementById("filter_semester");
    const courseGroupSelect = document.getElementById("filter_course_group");
    const subjectSelect = document.getElementById("subject");

    function fetchSubjects() {
        const year = yearSelect.value;
        const semester = semesterSelect.value;
        const course_group = courseGroupSelect.value;

        if (year && semester && course_group) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                subjectSelect.innerHTML = this.responseText;
            };
            xhr.send("action=get_subjects&year=" + year + "&semester=" + semester + "&course_group=" + course_group);
        } else {
            subjectSelect.innerHTML = "<option value=''>Select Subject</option>";
        }
    }

    yearSelect.addEventListener("change", fetchSubjects);
    semesterSelect.addEventListener("change", fetchSubjects);
    courseGroupSelect.addEventListener("change", fetchSubjects);
});
</script>

</body>
</html>
