<?php
// index.php - Complete Student CRUD in one file
// Database Configuration
$host = 'localhost';
$dbname = 'db_enrollment';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle actions
$action = $_GET['action'] ?? 'list';
$message = $_GET['msg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) { // CREATE or UPDATE
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $student_number = trim($_POST['student_number']);
        $fullname = trim($_POST['fullname']);
        $course = trim($_POST['course']);
        $year_level = (int)$_POST['year_level'];
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($id) {
            // UPDATE
            $stmt = $pdo->prepare("UPDATE students SET 
                student_number = ?, fullname = ?, course = ?, year_level = ?, 
                email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$student_number, $fullname, $course, $year_level, $email, $phone, $id]);
            $message = "Student updated successfully!";
        } else {
            // CREATE
            $stmt = $pdo->prepare("INSERT INTO students 
                (student_number, fullname, course, year_level, email, phone) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$student_number, $fullname, $course, $year_level, $email, $phone]);
            $message = "Student added successfully!";
        }
        $action = 'list';
    } 
    elseif (isset($_POST['delete'])) { // DELETE
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Student deleted successfully!";
        $action = 'list';
    }
}

// Get student for edit
$editStudent = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $editStudent = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$editStudent) {
        $message = "Student not found!";
        $action = 'list';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Enrollment System</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        h1, h2 { color: #333; }
        .message { 
            padding: 12px; 
            margin: 15px 0; 
            border-radius: 4px; 
            background: #d4edda; 
            color: #155724; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th { background: #f4f4f4; }
        .actions a {
            margin-right: 12px;
            text-decoration: none;
        }
        .edit { color: #0066cc; }
        .delete { color: #cc0000; }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
        }
        form {
            max-width: 600px;
            margin: 20px 0;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            width: 100%;
            padding: 9px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        .form-buttons {
            margin-top: 20px;
        }
        .cancel { 
            background: #6c757d; 
            margin-left: 10px;
        }
    </style>
</head>
<body>

<h1>Student Enrollment Management</h1>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($action === 'list'): ?>

    <a href="?action=add" class="btn">+ Add New Student</a>

    <?php
    $stmt = $pdo->query("SELECT * FROM students ORDER BY fullname");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if (empty($students)): ?>
        <p><em>No students registered yet...</em></p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student No.</th>
                <th>Full Name</th>
                <th>Course</th>
                <th>Year</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; foreach ($students as $student): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($student['student_number']) ?></td>
                <td><?= htmlspecialchars($student['fullname']) ?></td>
                <td><?= htmlspecialchars($student['course']) ?></td>
                <td><?= $student['year_level'] ?></td>
                <td><?= htmlspecialchars($student['email'] ?: '—') ?></td>
                <td><?= htmlspecialchars($student['phone'] ?: '—') ?></td>
                <td class="actions">
                    <a class="edit" href="?action=edit&id=<?= $student['id'] ?>">Edit</a>
                    <a class="delete" href="?action=delete&id=<?= $student['id'] ?>" 
                       onclick="return confirm('Delete <?= htmlspecialchars($student['fullname'], ENT_QUOTES) ?>?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

<?php elseif ($action === 'add' || $action === 'edit'): ?>

    <h2><?= $action === 'edit' ? 'Edit Student' : 'Add New Student' ?></h2>

    <form method="post">
        <?php if ($action === 'edit' && $editStudent): ?>
            <input type="hidden" name="id" value="<?= $editStudent['id'] ?>">
        <?php endif; ?>

        <label>Student Number *</label>
        <input type="text" name="student_number" 
               value="<?= $editStudent ? htmlspecialchars($editStudent['student_number']) : '' ?>" required>

        <label>Full Name *</label>
        <input type="text" name="fullname" 
               value="<?= $editStudent ? htmlspecialchars($editStudent['fullname']) : '' ?>" required>

        <label>Course/Program *</label>
        <input type="text" name="course" 
               value="<?= $editStudent ? htmlspecialchars($editStudent['course']) : '' ?>" required>

        <label>Year Level *</label>
        <select name="year_level" required>
            <?php for ($y = 1; $y <= 5; $y++): ?>
                <option value="<?= $y ?>" <?= ($editStudent && $editStudent['year_level'] == $y) ? 'selected' : '' ?>>
                    <?= $y ?><?= ($y == 1) ? 'st' : (($y == 2) ? 'nd' : (($y == 3) ? 'rd' : 'th')) ?> Year
                </option>
            <?php endfor; ?>
        </select>

        <label>Email</label>
        <input type="email" name="email" 
               value="<?= $editStudent ? htmlspecialchars($editStudent['email'] ?? '') : '' ?>">

        <label>Phone</label>
        <input type="text" name="phone" 
               value="<?= $editStudent ? htmlspecialchars($editStudent['phone'] ?? '') : '' ?>">

        <div class="form-buttons">
            <button type="submit" name="save" class="btn">
                <?= $action === 'edit' ? 'Update Student' : 'Save Student' ?>
            </button>
            <a href="?action=list" class="btn cancel">Cancel</a>
        </div>
    </form>

<?php elseif ($action === 'delete' && isset($_GET['id'])): ?>

    <?php
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT fullname FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student):
    ?>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete <strong><?= htmlspecialchars($student['fullname']) ?></strong>?</p>
        
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <button type="submit" name="delete" style="background:#dc3545; color:white; padding:12px 20px; border:none; border-radius:4px; cursor:pointer;">
                Yes, Delete Student
            </button>
            <a href="?action=list" style="margin-left:20px; text-decoration:none;">No, Cancel</a>
        </form>
    <?php else: ?>
        <p>Student not found!</p>
        <a href="?action=list">Back to list</a>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>