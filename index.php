<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-graduation-cap"></i> Student Records</h1>
        
        <div class="form-box">
            <h2 id="form-title"><i class="fas fa-user-plus"></i> Add New Student</h2>
            <form id="studentForm">
                <input type="hidden" id="studentId">
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name:</label>
                    <input type="text" id="name" required placeholder="Enter full name">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email:</label>
                    <input type="email" id="email" required placeholder="Enter email">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-book"></i> Course:</label>
                    <input type="text" id="course" required placeholder="Enter course">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone:</label>
                    <input type="text" id="phone" required placeholder="Enter phone">
                </div>
                
                <div class="buttons">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Student
                    </button>
                    <button type="button" class="btn-cancel" id="cancelBtn" style="display:none;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Student List -->
        <div class="student-list">
            <h2><i class="fas fa-list"></i> Student Records</h2>
            <div id="studentTable">
                <!-- Students will appear here -->
                <div class="loading">
                    <i class="fas fa-spinner fa-spin"></i> Loading students...
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Delete -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h3>
            <p>Are you sure you want to delete this student record?</p>
            <div class="modal-buttons">
                <button id="confirmDelete" class="btn-delete">Yes, Delete</button>
                <button id="cancelDelete" class="btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>