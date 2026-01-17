
let currentStudentId = null; // Track which student we're editing
let deleteStudentId = null; // Track which student to delete

// 1. WHEN PAGE LOADS
document.addEventListener('DOMContentLoaded', function() {
    loadStudents(); // Load all students
    
    // Handle form submission
    document.getElementById('studentForm').addEventListener('submit', handleFormSubmit);
    
    // Cancel button
    document.getElementById('cancelBtn').addEventListener('click', cancelEdit);
    
    // Delete modal buttons
    document.getElementById('confirmDelete').addEventListener('click', confirmDelete);
    document.getElementById('cancelDelete').addEventListener('click', cancelDelete);
});

// 2. LOAD ALL STUDENTS
function loadStudents() {
    fetch('process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=getAll'
    })
    .then(response => response.json())
    .then(data => {
        displayStudents(data);
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('studentTable').innerHTML = 
            '<div class="loading">Error loading students. Please try again.</div>';
    });
}

// 3. DISPLAY STUDENTS IN TABLE
function displayStudents(students) {
    const table = document.getElementById('studentTable');
    
    if (students.length === 0) {
        table.innerHTML = '<div class="loading">No students found. Add your first student!</div>';
        return;
    }
    
    let html = `
        <div class="student-item header">
            <div>Name</div>
            <div>Email</div>
            <div>Course</div>
            <div>Phone</div>
            <div>Actions</div>
        </div>
    `;
    
    students.forEach(student => {
        html += `
            <div class="student-item" id="student-${student.id}">
                <div>${student.name}</div>
                <div>${student.email}</div>
                <div>${student.course}</div>
                <div>${student.phone}</div>
                <div class="student-actions">
                    <button class="btn-edit" onclick="editStudent(${student.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn-delete" onclick="showDeleteModal(${student.id})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `;
    });
    
    table.innerHTML = html;
}

// 4. HANDLE FORM SUBMIT (Save or Update)
function handleFormSubmit(event) {
    event.preventDefault(); // Prevent page refresh
    
    const form = event.target;
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const course = document.getElementById('course').value;
    const phone = document.getElementById('phone').value;
    
    // Validation
    if (!name || !email || !course || !phone) {
        alert('Please fill all fields!');
        return;
    }
    
    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('course', course);
    formData.append('phone', phone);
    
    // If editing existing student
    if (currentStudentId) {
        formData.append('action', 'update');
        formData.append('id', currentStudentId);
    } else {
        formData.append('action', 'save');
    }
    
    // Send to server
    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            resetForm();
            loadStudents(); // Refresh list
        } else if (data.error) {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong!');
    });
}

// 5. EDIT STUDENT
function editStudent(id) {
    currentStudentId = id;
    
    // Change form title
    document.getElementById('form-title').innerHTML = '<i class="fas fa-user-edit"></i> Edit Student';
    document.getElementById('form-title').innerHTML += ` (ID: ${id})`;
    
    // Show cancel button
    document.getElementById('cancelBtn').style.display = 'inline-flex';
    
    // Fetch student data
    const formData = new FormData();
    formData.append('action', 'getSingle');
    formData.append('id', id);
    
    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(student => {
        if (student.error) {
            alert(student.error);
            return;
        }
        
        // Fill form with student data
        document.getElementById('name').value = student.name;
        document.getElementById('email').value = student.email;
        document.getElementById('course').value = student.course;
        document.getElementById('phone').value = student.phone;
        document.getElementById('studentId').value = student.id;
        
        // Scroll to form
        document.querySelector('.form-box').scrollIntoView({ behavior: 'smooth' });
    });
}

// 6. CANCEL EDIT
function cancelEdit() {
    resetForm();
}

// 7. SHOW DELETE CONFIRMATION
function showDeleteModal(id) {
    deleteStudentId = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

// 8. CONFIRM DELETE
function confirmDelete() {
    if (!deleteStudentId) return;
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', deleteStudentId);
    
    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.success);
            loadStudents(); // Refresh list
        }
        cancelDelete();
    });
}

// 9. CANCEL DELETE
function cancelDelete() {
    deleteStudentId = null;
    document.getElementById('deleteModal').style.display = 'none';
}

// 10. RESET FORM
function resetForm() {
    currentStudentId = null;
    document.getElementById('studentForm').reset();
    document.getElementById('form-title').innerHTML = '<i class="fas fa-user-plus"></i> Add New Student';
    document.getElementById('cancelBtn').style.display = 'none';
    document.getElementById('studentId').value = '';
}