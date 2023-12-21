<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];

    // Set default values for block_s and block_t to 1
    $block_s = 1;
    $block_t = 1;

    if ($user_type === 'student') {
        $student_level = isset($_POST['student_level']) ? $_POST['student_level'] : '';
        if ($student_level === '') {
            die("Please enter student level.");
        }
        $sql = "INSERT INTO student (student_id, student_name, email, password, phone, student_level, block_s) VALUES ('$user_id', '$user_name', '$email' , '$password', '$phone', '$student_level', '$block_s')";
    } else {
        $sql = "INSERT INTO teacher (teacher_id, teacher_name, email, password, phone, block_t) VALUES ('$user_id', '$user_name', '$email', '$password', '$phone', '$block_t')";
    }

    if ($connection->query($sql) === TRUE) {
        // Redirect to the login page after successful registration
        header("Location: index.php");
        exit();
    } else {
        // Log the error and provide a user-friendly message
        error_log("Error in registration: " . $sql . "\n" . $connection->error);

        // Redirect the user to the Registration page on error
        header("Location: registration.php?error=true");
        exit();
    }
}
?>
