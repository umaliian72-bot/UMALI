<?php

require_once 'database.php';

$action = $_POST['action'] ?? '';

// Handle different actions
switch($action) {
    case 'save':
        saveStudent();
        break;
    case 'getAll':
        getAllStudents();
        break;
    case 'getSingle':
        getSingleStudent();
        break;
    case 'update':
        updateStudent();
        break;
    case 'delete':
        deleteStudent();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function saveStudent() {
    global $conn;
    
    // Get data from form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("INSERT INTO students (name, email, course, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $course, $phone);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Student saved successfully!']);
    } else {
        echo json_encode(['error' => 'Error saving student']);
    }
}
function getAllStudents() {
    global $conn;
    
    $sql = "SELECT * FROM students ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    $students = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    
    echo json_encode($students);
}

function getSingleStudent() {
    global $conn;
    
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
}

function updateStudent() {
    global $conn;
    
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $phone = $_POST['phone'];
    
    $stmt = $conn->prepare("UPDATE students SET name=?, email=?, course=?, phone=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $course, $phone, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Student updated successfully!']);
    } else {
        echo json_encode(['error' => 'Error updating student']);
    }
}

function deleteStudent() {
    global $conn;
    
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Student deleted successfully!']);
    } else {
        echo json_encode(['error' => 'Error deleting student']);
    }
}

// Close connection
$conn->close();
?>