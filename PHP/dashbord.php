<?php
include 'dbconnect.php';

// Fetch distinct batches for dropdown filter
$batches_result = mysqli_query($conn, "SELECT DISTINCT batch FROM student WHERE batch IS NOT NULL AND batch != '' ORDER BY batch ASC");

// Get selected batch filter from URL (GET param)
$selected_batch = isset($_GET['batch']) ? $_GET['batch'] : '';

// Base queries
$student_query = "SELECT COUNT(*) AS total FROM student";
$results_query = "SELECT COUNT(*) AS total FROM results";
$pass_query = "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN grade IN ('A', 'B', 'C') THEN 1 ELSE 0 END) AS pass_count
    FROM results
";

// Modify queries if a batch filter is selected (and not 'all')
if ($selected_batch !== '' && $selected_batch !== 'all') {
    $safe_batch = mysqli_real_escape_string($conn, $selected_batch);

    $student_query .= " WHERE batch = '$safe_batch'";

    $results_query = "
        SELECT COUNT(*) AS total
        FROM results
        JOIN student ON results.student_id = student.student_id
        WHERE student.batch = '$safe_batch'
    ";

    $pass_query = "
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN grade IN ('A', 'B', 'C') THEN 1 ELSE 0 END) AS pass_count
        FROM results
        JOIN student ON results.student_id = student.student_id
        WHERE student.batch = '$safe_batch'
    ";
}

// Execute queries
$student_res = mysqli_query($conn, $student_query);
$student_row = mysqli_fetch_assoc($student_res);
$total_students = $student_row['total'] ?? 0;

$results_res = mysqli_query($conn, $results_query);
$results_row = mysqli_fetch_assoc($results_res);
$total_results = $results_row['total'] ?? 0;

$pass_res = mysqli_query($conn, $pass_query);
$pass_row = mysqli_fetch_assoc($pass_res);
$total = $pass_row['total'] ?? 0;
$pass_count = $pass_row['pass_count'] ?? 0;
$pass_percentage = $total > 0 ? round(($pass_count / $total) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>

    <!-- Separate CSS -->
    <link rel="stylesheet" href="../CSS/dashbord.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
        <p>Manage students and results.</p>

        <!-- Batch Filter Form -->
        <form method="GET" action="dashbord.php" class="batch-filter-form">
            <label for="batch">Filter by Batch:</label>
            <select name="batch" id="batch" onchange="this.form.submit()">
                <option value="all" <?php if($selected_batch == 'all' || $selected_batch == '') echo 'selected'; ?>>All</option>
                <?php while ($batch_row = mysqli_fetch_assoc($batches_result)) { ?>
                    <option value="<?php echo htmlspecialchars($batch_row['batch']); ?>" 
                        <?php if($batch_row['batch'] == $selected_batch) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($batch_row['batch']); ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <div class="summary-cards">
            <div class="card card-students">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>
            <div class="card card-results">
                <h3>Total Results</h3>
                <p><?php echo $total_results; ?></p>
            </div>
            <div class="card card-pass">
                <h3>Pass Percentage</h3>
                <p><?php echo $pass_percentage; ?>%</p>
            </div>
        </div>
    </div>
</div>

<script>
    console.log('Dashboard loaded');
</script>
</body>
</html>
