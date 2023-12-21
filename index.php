<?php
session_start();

require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Validate user credentials and check block status
    $sql = "SELECT * FROM $user_type WHERE ".$user_type."_id='$user_id' AND password='$password'";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error in SQL query: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);

        // Check if the user is blocked
        if (($user_type === 'student' && $user_data['block_s'] == 1) || 
            ($user_type === 'teacher' && $user_data['block_t'] == 1)) {
            echo "<script>alert('Email is not approved yet.');</script>";
        } else {
            // User is not blocked, proceed with login
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_type'] = $user_type;

            switch ($user_type) {
                case 'student':
                    header("Location: student_dashboard.php");
                    exit();
                    break;
                case 'teacher':
                    header("Location: teacher_dashboard.php");
                    exit();
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    exit();
                    break;
            }
        }
    } else {
        echo "<script>alert('Login failed. Invalid user id or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

         </style>
</head>
<body>
    <form action="" method="post">
        <h1>Login</h1>
        <input type="text" name="user_id" placeholder="User ID" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="user_type">
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="admin">Admin</option>
        </select><br>
        <input type="submit" name="login" value="Login">
        <p>Don't have an account? <a href="registration.php">Register</a></p>
    </form>
</body>
</html>
