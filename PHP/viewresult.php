<?php
include("dbconnect.php");

// Helper function for prepared statements and filtering
function buildFilterQuery(&$params, &$types, $filters) {
    $sql = " WHERE 1=1";
    if (!empty($filters['year'])) {
        $sql .= " AND year = ?";
        $params[] = $filters['year'];
        $types .= "i";
    }
    if (!empty($filters['semester'])) {
        $sql .= " AND semester = ?";
        $params[] = $filters['semester'];
        $types .= "i";
    }
    if (!empty($filters['course_group'])) {
        $sql .= " AND course_group = ?";
        $params[] = $filters['course_group'];
        $types .= "s";
    }
    if (!empty($filters['subject_id'])) {
        $sql .= " AND id = ?";
        $params[] = $filters['subject_id'];
        $types .= "i";
    }
    return $sql;
}

// AJAX handler for fetching subjects based on filters
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_subjects') {
    $filters = [
        'year' => $_POST['year'] ?? '',
        'semester' => $_POST['semester'] ?? '',
        'course_group' => $_POST['course_group'] ?? '',
    ];

    $params = [];
    $types = '';
    $where = buildFilterQuery($params, $types, $filters);

    $stmt = $conn->prepare("SELECT id, subject_name FROM subjects $where ORDER BY subject_name");
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [['id' => '', 'name' => 'All']];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = ['id' => $row['id'], 'name' => $row['subject_name']];
    }
    header('Content-Type: application/json');
    echo json_encode($subjects);
    exit;
}

// Main page filtering inputs from GET
$year = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$course_group = $_GET['course_group'] ?? '';
$subject_id = $_GET['subject'] ?? '';
$index_number = $_GET['index_number'] ?? '';
$batch = $_GET['batch'] ?? '';

// Fetch student details if index number is provided
$studentDetails = [];
$studentResults = [];
$subjects = [];
$gpaCalculations = [
    'semester_gpa' => [],
    'year_gpa' => [],
    'overall_gpa' => null
];

if (!empty($index_number)) {
    // Fetch student details
    $paramsStudent = [];
    $typesStudent = '';
    $whereStudent = " WHERE s.student_id = ?";
    $paramsStudent[] = $index_number;
    $typesStudent .= "s";
    
    if (!empty($batch)) {
        $whereStudent .= " AND s.batch = ?";
        $paramsStudent[] = $batch;
        $typesStudent .= "s";
    }
    
    $stmtStudent = $conn->prepare("
        SELECT s.student_id, s.name, s.batch 
        FROM student s
        $whereStudent
        LIMIT 1
    ");
    $stmtStudent->bind_param($typesStudent, ...$paramsStudent);
    $stmtStudent->execute();
    $studentDetails = $stmtStudent->get_result()->fetch_assoc();
    
    // Fetch student's results if student exists
    if ($studentDetails) {
        $paramsResults = [];
        $typesResults = '';
        $whereResults = " WHERE r.student_id = ?";
        $paramsResults[] = $index_number;
        $typesResults .= "s";
        
        if (!empty($year)) {
            $whereResults .= " AND r.year = ?";
            $paramsResults[] = $year;
            $typesResults .= "i";
        }
        if (!empty($semester)) {
            $whereResults .= " AND r.semester = ?";
            $paramsResults[] = $semester;
            $typesResults .= "i";
        }
        if (!empty($course_group)) {
            $whereResults .= " AND sub.course_group = ?";
            $paramsResults[] = $course_group;
            $typesResults .= "s";
        }
        if (!empty($subject_id)) {
            $whereResults .= " AND r.subject_id = ?";
            $paramsResults[] = $subject_id;
            $typesResults .= "i";
        }
        
        $stmtResults = $conn->prepare("
            SELECT r.subject_id, sub.subject_name, r.grade, r.year, r.semester, 1 as credits
            FROM results r
            JOIN subjects sub ON r.subject_id = sub.id
            $whereResults
            ORDER BY r.year, r.semester, sub.subject_name
        ");
        $stmtResults->bind_param($typesResults, ...$paramsResults);
        $stmtResults->execute();
        $studentResults = $stmtResults->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Calculate GPAs
        $gradePoints = [
            'A+' => 4.0,
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D+' => 1.3,
            'D' => 1.0,
            'E' => 0.0,
            'F' => 0.0
        ];
        
        if (!empty($studentResults)) {
            $totalQualityPoints = 0;
            $totalCredits = 0;
            $currentYearSemester = [];
            
            foreach ($studentResults as $result) {
                $yearSem = $result['year'] . '_' . $result['semester'];
                $grade = $result['grade'];
                $credits = $result['credits'] ?? 1; // Default to 1 credit
                
                if (!isset($currentYearSemester[$yearSem])) {
                    $currentYearSemester[$yearSem] = [
                        'quality_points' => 0,
                        'credits' => 0
                    ];
                }
                
                if (isset($gradePoints[$grade])) {
                    $currentYearSemester[$yearSem]['quality_points'] += $gradePoints[$grade] * $credits;
                    $currentYearSemester[$yearSem]['credits'] += $credits;
                    
                    $totalQualityPoints += $gradePoints[$grade] * $credits;
                    $totalCredits += $credits;
                }
            }
            
            // Calculate semester GPAs
            foreach ($currentYearSemester as $yearSem => $data) {
                list($year, $semester) = explode('_', $yearSem);
                if ($data['credits'] > 0) {
                    $gpa = $data['quality_points'] / $data['credits'];
                    $gpaCalculations['semester_gpa'][] = [
                        'year' => $year,
                        'semester' => $semester,
                        'gpa' => round($gpa, 2),
                        'credits' => $data['credits']
                    ];
                }
            }
            
            // Calculate year GPAs
            $yearData = [];
            foreach ($gpaCalculations['semester_gpa'] as $semesterGpa) {
                $year = $semesterGpa['year'];
                if (!isset($yearData[$year])) {
                    $yearData[$year] = [
                        'quality_points' => 0,
                        'credits' => 0
                    ];
                }
                $yearData[$year]['quality_points'] += $semesterGpa['gpa'] * $semesterGpa['credits'];
                $yearData[$year]['credits'] += $semesterGpa['credits'];
            }
            
            foreach ($yearData as $year => $data) {
                if ($data['credits'] > 0) {
                    $gpaCalculations['year_gpa'][] = [
                        'year' => $year,
                        'gpa' => round($data['quality_points'] / $data['credits'], 2)
                    ];
                }
            }
            
            // Calculate overall GPA
            if ($totalCredits > 0) {
                $gpaCalculations['overall_gpa'] = round($totalQualityPoints / $totalCredits, 2);
            }
        }
    }
    
    // Fetch all subjects for the dropdown (even if no student found)
    $filtersSubjects = ['year' => $year, 'semester' => $semester, 'course_group' => $course_group];
    $paramsSubjects = [];
    $typesSubjects = '';
    $whereSubjects = buildFilterQuery($paramsSubjects, $typesSubjects, $filtersSubjects);
    
    $stmtSubjects = $conn->prepare("SELECT id, subject_name FROM subjects $whereSubjects ORDER BY subject_name ASC");
    if ($paramsSubjects) {
        $stmtSubjects->bind_param($typesSubjects, ...$paramsSubjects);
    }
    $stmtSubjects->execute();
    $resSubjects = $stmtSubjects->get_result();
    while ($row = $resSubjects->fetch_assoc()) {
        $subjects[$row['id']] = $row['subject_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Results</title>
    <link rel="stylesheet" href="../CSS/viewresult.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        <h1>View Results</h1>

        <form method="GET" id="filterForm" class="search-form filter-box">
            <div class="filter-group">
                <label for="batch">Batch</label>
                <select name="batch" id="batch">
                    <option value="">All</option>
                    <option value="2023" <?= $batch === '2023' ? 'selected' : '' ?>>2023</option>
                    <option value="2022" <?= $batch === '2022' ? 'selected' : '' ?>>2022</option>
                    <option value="2021" <?= $batch === '2021' ? 'selected' : '' ?>>2021</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="index_number">Index Number</label>
                <input type="text" name="index_number" id="index_number" 
                       placeholder="Enter student ID" value="<?= htmlspecialchars($index_number) ?>">
            </div>
            
            <div class="filter-group">
                <label for="year">Year</label>
                <select name="year" id="year">
                    <option value="">All</option>
                    <option value="1" <?= $year === '1' ? 'selected' : '' ?>>1st Year</option>
                    <option value="2" <?= $year === '2' ? 'selected' : '' ?>>2nd Year</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="semester">Semester</label>
                <select name="semester" id="semester">
                    <option value="">All</option>
                    <option value="1" <?= $semester === '1' ? 'selected' : '' ?>>1st Semester</option>
                    <option value="2" <?= $semester === '2' ? 'selected' : '' ?>>2nd Semester</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="course_group">Group</label>
                <select name="course_group" id="course_group">
                    <option value="">All</option>
                    <option value="IT" <?= $course_group === 'IT' ? 'selected' : '' ?>>IT</option>
                    <option value="ENGLISH" <?= $course_group === 'ENGLISH' ? 'selected' : '' ?>>ENGLISH</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="subject">Subject</label>
                <select name="subject" id="subject">
                    <option value="">All</option>
                    <?php foreach ($subjects as $id => $name): ?>
                        <option value="<?= $id ?>" <?= $subject_id == $id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn-primary">Search</button>
        </form>

        <?php if (!empty($index_number)): ?>
            <div class="student-results-container">
                <?php if (!empty($studentDetails)): ?>
                    <div class="student-card" id="student-results-content">
                        <div class="export-container">
                            <button id="export-pdf" class="btn-export">
                                <i class="fas fa-file-pdf"></i> Export as PDF
                            </button>
                        </div>
                        <div class="student-header">
                            <h2><?= htmlspecialchars($studentDetails['name']) ?></h2>
                            <div class="student-meta">
                                <span><strong>Index:</strong> <?= htmlspecialchars($studentDetails['student_id']) ?></span>
                                <span><strong>Batch:</strong> <?= htmlspecialchars($studentDetails['batch']) ?></span>
                            </div>
                        </div>
                        
                        <?php if (!empty($studentResults)): ?>
                            <div class="results-list">
                                <div class="results-header">
                                    <span>Subject</span>
                                    <span>Year</span>
                                    <span>Semester</span>
                                    <span>Grade</span>
                                </div>
                                
                                <?php foreach ($studentResults as $result): ?>
                                    <div class="result-item">
                                        <span class="subject"><?= htmlspecialchars($result['subject_name']) ?></span>
                                        <span class="year"><?= htmlspecialchars($result['year']) ?></span>
                                        <span class="semester"><?= htmlspecialchars($result['semester']) ?></span>
                                        <span class="grade"><?= htmlspecialchars($result['grade']) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- GPA Summary Section -->
                            <div class="gpa-summary">
                                <h3>GPA Summary</h3>
                                
                                <!-- Semester GPAs -->
                                <?php if (!empty($gpaCalculations['semester_gpa'])): ?>
                                    <div class="gpa-section">
                                        <h4>Semester GPAs</h4>
                                        <div class="gpa-grid">
                                            <?php foreach ($gpaCalculations['semester_gpa'] as $semesterGpa): ?>
                                                <div class="gpa-item">
                                                    <span>Year <?= $semesterGpa['year'] ?> Semester <?= $semesterGpa['semester'] ?></span>
                                                    <span class="gpa-value"><?= $semesterGpa['gpa'] ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Year GPAs -->
                                <?php if (!empty($gpaCalculations['year_gpa'])): ?>
                                    <div class="gpa-section">
                                        <h4>Year GPAs</h4>
                                        <div class="gpa-grid">
                                            <?php foreach ($gpaCalculations['year_gpa'] as $yearGpa): ?>
                                                <div class="gpa-item">
                                                    <span>Year <?= $yearGpa['year'] ?></span>
                                                    <span class="gpa-value"><?= $yearGpa['gpa'] ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Overall GPA -->
                                <div class="gpa-section overall-gpa">
                                    <h4>Overall GPA</h4>
                                    <div class="gpa-item">
                                        <span>All Results</span>
                                        <span class="gpa-value"><?= $gpaCalculations['overall_gpa'] ?? 'N/A' ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="no-results-message">No results found for this student with the selected filters.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="no-results-message">No student found with the provided index number and batch.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="search-prompt">
                <i class="fas fa-search fa-3x"></i>
                <p>Enter an index number to view student results</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Load subjects dynamically based on filters
function loadSubjects() {
    $.post('viewresult.php', {
        action: 'get_subjects',
        year: $('#year').val(),
        semester: $('#semester').val(),
        course_group: $('#course_group').val()
    }, function(response) {
        const subjectSelect = $('#subject');
        subjectSelect.empty();
        subjectSelect.append(new Option('All', ''));
        response.forEach(function(subj) {
            subjectSelect.append(new Option(subj.name, subj.id));
        });
        // Retain the selected subject if any
        subjectSelect.val('<?= htmlspecialchars($subject_id) ?>');
    }, 'json');
}

$(document).ready(function() {
    loadSubjects();
    $('#year, #semester, #course_group').on('change', function() {
        $('#subject').val('');
        loadSubjects();
    });
    
    // Export to PDF functionality
    $('#export-pdf').on('click', function() {
        const element = document.getElementById('student-results-content');
        const opt = {
            margin: 10,
            filename: 'student_results_<?= $index_number ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    });
});
</script>
</body>
</html>