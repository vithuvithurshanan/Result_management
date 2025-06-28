<?php
session_start();
include("dbconnect.php");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = "";
$results = array();
$show_all = isset($_GET['show_all']); // Check if we should show all results

// Get student name
$student_query = "SELECT name FROM student WHERE student_id = '$student_id'";
$student_result = mysqli_query($conn, $student_query);
if ($row = mysqli_fetch_assoc($student_result)) {
    $student_name = $row['name'];
}

// Get all results from database
$query = "SELECT s.subject_name, r.grade, r.year, r.semester 
          FROM results r 
          JOIN subjects s ON r.subject_id = s.id 
          WHERE r.student_id = '$student_id'
          ORDER BY r.year DESC, r.semester DESC, s.subject_name";

$result = mysqli_query($conn, $query);
$all_results = array();
while ($row = mysqli_fetch_assoc($result)) {
    $all_results[] = $row;
}

// Determine which results to display
$display_results = $all_results; // Show all by default
$result_count = count($all_results);
$show_limit = false;

if (!$show_all && $result_count > 5) {
    $display_results = array_slice($all_results, 0, 5);
    $show_limit = true;
}

// Calculate GPA
$gpa = 0;
$total_credits = 0;
$grade_points = [
    'A+' => 4.0, 'A' => 4.0, 'A-' => 3.7,
    'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
    'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
    'F' => 0.0, 'A/B' => 0.0
];

$gpa_query = "SELECT grade FROM results WHERE student_id = '$student_id'";
$gpa_result = mysqli_query($conn, $gpa_query);

while ($row = mysqli_fetch_assoc($gpa_result)) {
    if (isset($grade_points[$row['grade']])) {
        $gpa += $grade_points[$row['grade']] * 3; // Assuming 3 credits per subject
        $total_credits += 3;
    }
}

if ($total_credits > 0) {
    $gpa = $gpa / $total_credits;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../CSS/studentview.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
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
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="noti.php"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="logout.php" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <h2>Welcome, <?= htmlspecialchars($student_name) ?></h2>
                    <p>Student ID: <?= htmlspecialchars($student_id) ?></p>
                </div>
                
                <!-- Fixed GPA Section -->
                <div class="gpa-card">
                    <h3>Your Current GPA</h3>
                    <div class="gpa-value"><?= number_format($gpa, 2) ?></div>
                    <p>Based on all completed courses</p>
                </div>
                
                <!-- Results Section -->
                <div class="recent-results">
                    <h3><i class="fas fa-poll"></i> <?= $show_all || $result_count <= 5 ? 'All Results' : 'Recent Results' ?></h3>
                    
                    <?php if (!empty($display_results)): ?>
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($display_results as $result): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($result['subject_name']) ?></td>
                                        <td><?= htmlspecialchars($result['grade']) ?></td>
                                        <td>Year <?= htmlspecialchars($result['year']) ?></td>
                                        <td>Semester <?= htmlspecialchars($result['semester']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="button-group">
                            <?php if ($show_limit): ?>
                                <a href="?show_all=1" class="view-all-btn">View All Results (<?= $result_count ?>)</a>
                            <?php elseif ($show_all && $result_count > 5): ?>
                                <a href="?" class="view-all-btn">Show Recent Results</a>
                            <?php endif; ?>
                            
                            <button id="exportPdf" class="export-btn">
                                <i class="fas fa-file-pdf"></i> Export as PDF
                            </button>
                        </div>
                    <?php else: ?>
                        <p>No results found. Your results will appear here once available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const { jsPDF } = window.jspdf;
        
        document.getElementById('exportPdf').addEventListener('click', function() {
            // Create a new PDF document
            const doc = new jsPDF();
            
            // Add title and student info
            doc.setFontSize(18);
            doc.text('Academic Results Transcript', 105, 20, { align: 'center' });
            doc.setFontSize(12);
            doc.text(`Student: ${'<?= htmlspecialchars($student_name) ?>'}`, 14, 40);
            doc.text(`Student ID: ${'<?= htmlspecialchars($student_id) ?>'}`, 14, 50);
            doc.text(`GPA: ${'<?= number_format($gpa, 2) ?>'}`, 14, 60);
            doc.text(`Date: ${new Date().toLocaleDateString()}`, 14, 70);
            
            // Prepare table data
            const headers = ['Subject', 'Grade', 'Year', 'Semester'];
            
            const data = [];
            <?php foreach ($all_results as $result): ?>
                data.push([
                    '<?= htmlspecialchars($result['subject_name']) ?>',
                    '<?= htmlspecialchars($result['grade']) ?>',
                    'Year <?= htmlspecialchars($result['year']) ?>',
                    'Semester <?= htmlspecialchars($result['semester']) ?>'
                ]);
            <?php endforeach; ?>
            
            // Add the table
            doc.autoTable({
                head: [headers],
                body: data,
                startY: 80,
                styles: {
                    fontSize: 10,
                    cellPadding: 3,
                    valign: 'middle'
                },
                headStyles: {
                    fillColor: [44, 62, 80],
                    textColor: 255
                },
                alternateRowStyles: {
                    fillColor: [240, 240, 240]
                }
            });
            
            // Save the PDF
            doc.save('<?= htmlspecialchars($student_name) ?>_Results_<?= date('Y-m-d') ?>.pdf');
        });
    });
    </script>
</body>
</html>