<?php
$conn = new mysqli("localhost", "root", "", "ati_rms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle AJAX Edit Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_student') {
    $id = $_POST['edit_student_id'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check duplicate email for other students
    $check_stmt = $conn->prepare("SELECT student_id FROM student WHERE email = ? AND student_id != ?");
    $check_stmt->bind_param("ss", $email, $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This email is already used by another student.']);
    } else {
        $stmt = $conn->prepare("UPDATE student SET name=?, department=?, email=?, phone=? WHERE student_id=?");
        $stmt->bind_param("sssss", $name, $department, $email, $phone, $id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Student updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating student: ' . $stmt->error]);
        }
        $stmt->close();
    }
    $check_stmt->close();
    exit;
}

// Handle deletion
if (isset($_GET['delete_student_id'])) {
    $id = $_GET['delete_student_id'];

    $stmt = $conn->prepare("DELETE FROM student WHERE student_id = ?");
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        $message = "Student deleted successfully!";
    } else {
        $message = "Error deleting student: " . $stmt->error;
    }
    $stmt->close();
}

// Search and Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $limit;

$search_like = "%" . $search . "%";

$stmt = $conn->prepare("SELECT * FROM student WHERE 
    CAST(student_id AS CHAR) LIKE ? OR
    name LIKE ? OR
    department LIKE ?
    LIMIT ?, ?");
$stmt->bind_param("sssii", $search_like, $search_like, $search_like, $start, $limit);
$stmt->execute();
$students = $stmt->get_result();

$count_stmt = $conn->prepare("SELECT COUNT(*) FROM student WHERE 
    CAST(student_id AS CHAR) LIKE ? OR
    name LIKE ? OR
    department LIKE ?");
$count_stmt->bind_param("sss", $search_like, $search_like, $search_like);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_students = $count_result->fetch_row()[0];
$total_pages = ceil($total_students / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <link rel="stylesheet" href="../CSS/viewstudent.css">
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
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>All Students</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="GET">
            <input type="text" name="search" class="search-bar" placeholder="Search by ID, Name or Department..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn search-btn">Search</button>
        </form>

        <div class="top-right">
            <a href="addstu.php" class="btn">+ Add New Student</a>
        </div>
<br>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Department</th><th>Email</th><th>Phone</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($stu = $students->fetch_assoc()): ?>
                    <tr id="row_<?= htmlspecialchars($stu['student_id']) ?>">
                        <td><?= htmlspecialchars($stu['student_id']) ?></td>
                        <td class="name"><?= htmlspecialchars($stu['name']) ?></td>
                        <td class="department"><?= htmlspecialchars($stu['department']) ?></td>
                        <td class="email"><?= htmlspecialchars($stu['email']) ?></td>
                        <td class="phone"><?= htmlspecialchars($stu['phone']) ?></td>
                        <td>
                            <a href="#" onclick='editStudent(<?= json_encode($stu) ?>)'>Edit</a> |
                            <a href="?delete_student_id=<?= urlencode($stu['student_id']) ?>" onclick="return confirm('Are you sure to delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($students->num_rows === 0): ?>
                    <tr><td colspan="6" style="text-align:center;">No students found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i === $page) ? 'current' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">×</span>
            <h3>Edit Student</h3>
            <form id="editForm">
                <input type="hidden" name="edit_student_id" id="edit_student_id">
                <label>Name</label>
                <input type="text" name="name" id="edit_name" required>
                <label>Department</label>
                <input type="text" name="department" id="edit_department" required>
                <label>Email</label>
                <input type="email" name="email" id="edit_email" required>
                <label>Phone</label>
                <input type="text" name="phone" id="edit_phone" required>
                <button type="submit">Update Student</button>
                <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            </form>
            <div id="modalMsg"></div>
        </div>
    </div>
</div>

<script>
    function editStudent(data) {
        document.getElementById('edit_student_id').value = data.student_id;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_department').value = data.department;
        document.getElementById('edit_email').value = data.email;
        document.getElementById('edit_phone').value = data.phone;
        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
        document.getElementById('modalMsg').innerHTML = '';
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'edit_student');

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalMsg').innerHTML = data.message;
            if (data.status === 'success') {
                const id = document.getElementById('edit_student_id').value;
                document.querySelector(`#row_${id} .name`).textContent = document.getElementById('edit_name').value;
                document.querySelector(`#row_${id} .department`).textContent = document.getElementById('edit_department').value;
                document.querySelector(`#row_${id} .email`).textContent = document.getElementById('edit_email').value;
                document.querySelector(`#row_${id} .phone`).textContent = document.getElementById('edit_phone').value;
                setTimeout(() => {
                    closeModal();
                }, 1500);
            }
        })
        .catch(err => {
            document.getElementById('modalMsg').innerHTML = 'An error occurred.';
        });
    });

    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeModal();
        }
    };
</script>
</body>
</html>
