<?php
require_once 'connection.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        body {
            background-color: #A0F9A0; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px; 
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }
        select {
            width: 50%;
            padding: 10px;
            background-color: #87ceeb;
            margin-bottom: 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 20%;
            padding: 10px;
            background-color: #87ceeb;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        a {
            color: #87ceeb;
            text-decoration: none;
        }
        .login-link {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form action="register_process.php" method="post">
        <h1>Registration</h1>
        <input type="text" name="user_id" placeholder="User ID" required><br>
        <input type="text" name="user_name" placeholder="User Name" required><br>
        <input type="text" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="text" name="phone" placeholder="Phone" required><br>
        <select name="user_type">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select><br>
        
        <!-- Additional fields for student level -->
        <div id="student_level_field" style="display: none;">
            <input type="text" name="student_level" placeholder="Student Level">
        </div>
        
        <input type="submit" value="Register">
        
        <p class="login-link">Already have an account? <a href="index.php">Login</a></p>
    </form>

    <script>
        function showStudentLevel() {
            var userType = document.querySelector('select[name="user_type"]').value;
            var studentLevelField = document.getElementById('student_level_field');
            if (userType === 'student') {
                studentLevelField.style.display = 'block';
            } else {
                studentLevelField.style.display = 'none';
            }
        }
        document.querySelector('select[name="user_type"]').addEventListener('change', showStudentLevel);
    </script>
</body>
</html>