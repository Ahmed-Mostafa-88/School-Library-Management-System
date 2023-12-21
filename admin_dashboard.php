<?php
// Start session
session_start();

// Check if the user is logged in as an admin
if (!(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin')) {
    header("Location: index.php"); // Redirect unauthorized users to login page
    exit();
}

// Include your database connection file
include('connection.php');

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
$books_query = "SELECT Book.book_id, Book.book_name, Teacher.teacher_name, Book.book_request, book.book_url FROM Book
                LEFT JOIN Teacher ON Book.publisher = Teacher.teacher_id";


$books_result = mysqli_query($connection, $books_query);

// Check if the query was successful
if ($books_result) {
    // Fetch the books data into an array
    $books = mysqli_fetch_all($books_result, MYSQLI_ASSOC);
} else {
    // Handle the case where the query fails
    die("Error in SQL query: " . mysqli_error($connection));
}

// Handle book update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update_request'])) {
    $book_id = $_POST['book_id'];
    $update_request = $_POST['update_request'];

    // Update book request in the database
    $update_request_query = "UPDATE Book SET book_request = '$update_request' WHERE book_id = '$book_id'";
    $update_request_result = mysqli_query($connection, $update_request_query);

    if (!$update_request_result) {
        die("Error updating book request: " . mysqli_error($connection));
    }

    // Redirect to avoid form resubmission on page refresh
    header("Location: admin_dashboard.php");
    exit();
}
// Handle admin functionalities
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update block status for students
    if (isset($_POST['update_block_s'])) {
        $student_id = $_POST['student_id'];
        $block_s = $_POST['block_s'];

        // Update block status in the database
        $update_query_s = "UPDATE student SET block_s = '$block_s' WHERE student_id = '$student_id'";
        mysqli_query($connection, $update_query_s);
    }

    // Update block status for teachers
    if (isset($_POST['update_block_t'])) {
        $teacher_id = $_POST['teacher_id'];
        $block_t = $_POST['block_t'];

        // Update block status in the database
        $update_query_t = "UPDATE teacher SET block_t = '$block_t' WHERE teacher_id = '$teacher_id'";
        mysqli_query($connection, $update_query_t);
    }
}
if (isset($_POST['remove_book'])) {
        $book_id_to_remove = $_POST['book_id'];

        // Implement the code to remove the book from the database
        $remove_book_query = "DELETE FROM Book WHERE book_id = '$book_id_to_remove'";
        $result_remove_book = mysqli_query($connection, $remove_book_query);

        if (!$result_remove_book) {
            die("Error removing book: " . mysqli_error($connection));
        }}


// Fetch student data
$student_query = "SELECT student_id, student_name, block_s FROM student";
$student_result = mysqli_query($connection, $student_query);
$students = mysqli_fetch_all($student_result, MYSQLI_ASSOC);
if (isset($_POST['remove_student'])) {
        $student_id_to_remove = $_POST['student_id'];

        // Implement the code to remove the student from the database
        $remove_student_query = "DELETE FROM student WHERE student_id = '$student_id_to_remove'";
        $result_remove_student = mysqli_query($connection, $remove_student_query);

        if (!$result_remove_student) {
            die("Error removing student: " . mysqli_error($connection));
        }}

// Fetch teacher data
$teacher_query = "SELECT teacher_id, teacher_name, block_t FROM teacher";
$teacher_result = mysqli_query($connection, $teacher_query);
$teachers = mysqli_fetch_all($teacher_result, MYSQLI_ASSOC);
if (isset($_POST['remove_teacher'])) {
        $teacher_id_to_remove = $_POST['teacher_id'];

        // Implement the code to remove the teacher from the database
        $remove_teacher_query = "DELETE FROM teacher WHERE teacher_id = '$teacher_id_to_remove'";
        $result_remove_teacher = mysqli_query($connection, $remove_teacher_query);

        if (!$result_remove_teacher) {
            die("Error removing teacher: " . mysqli_error($connection));
        }}


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_term'])) {
    // Handle book search
    $search_term = mysqli_real_escape_string($connection, $_POST['search_term']);
    
    // Assuming 'books' is the correct table name
    $sql = "SELECT Book.book_id, Book.book_name, Book.description, Book.date_published, Teacher.teacher_name, Book.book_url
            FROM Book
            LEFT JOIN Teacher ON Book.publisher = Teacher.teacher_id
            WHERE Book.book_id LIKE '%$search_term%' 
            OR Book.book_name LIKE '%$search_term%' 
            OR Teacher.teacher_name LIKE '%$search_term%'
            "; 
    
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error in SQL query: " . mysqli_error($connection));
        echo "<script>alert('book is not found');</script>";
    }else{
        
    }
    

    // Fetch the search results for the student
    $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
     <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #A0F9A0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        header {
            background-color: #87ceeb;
            text-align: center;
            padding: 10px;
        }
        h1 {
            margin: 20px 0;
            padding: 10px;
        }
        .container {
            margin: 20px;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #87ceeb;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #87ceeb;
        }
        .logout-form {
            margin-top: 20px;
        }
        input[type="submit"] {
            width: 100px;
            padding: 10px;
            background-color: #87ceeb;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="text"] {
            width: 60%;
            padding: 10px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-button {
            padding: 10px 20px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin!</h1>
     <h2>Manage Books</h2>

<!-- Display Books Table -->
<h3>Books</h3>
<table border="1">
    <thead>
        <tr>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>Teacher Name</th>
            <th>Book URL</th>
            <th>Book Request</th>
            <th>Update Request</th>
            <th>Remove Book</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($books as $book) : ?>
            <tr>
                <td><?php echo $book['book_id']; ?></td>
                <td><?php echo $book['book_name']; ?></td>
                <td><?php echo $book['teacher_name']; ?></td>
                <td><?php echo $book['book_url']; ?></td>
                <td><a href="<?php echo $book['book_url']; ?>" target="_blank"><?php echo $book['book_url']; ?></a></td>
                <td>
    <form method="post" action="">
        <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
        <label>
            <input type="radio" name="update_request" value="1" <?php echo ($book['book_request'] == 1) ? 'checked' : ''; ?>>
            Approve
        </label>
        <label>
            <input type="radio" name="update_request" value="0" <?php echo ($book['book_request'] == 0) ? 'checked' : ''; ?>>
            Reject
        </label>
        <input type="submit" name="submit_update_request" value="Submit">
    </form>
</td>
<td>
                        <!-- Remove Book Form -->
                        <form method="post" action="">
                            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                            <input type="submit" name="remove_book" value="Remove">
                        </form>
                    </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>    
    
    <h2>Manage Members</h2>
    <h3>Manage Students</h3>
<table border="1">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Access Status</th>
            <th>Update Access</th>
            <th>Remove Student</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student) : ?>
            <tr>
                <td><?php echo $student['student_id']; ?></td>
                <td><?php echo $student['student_name']; ?></td>
                <td><?php echo $student['block_s']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                        <label>
                            <input type="radio" name="block_s" value="1" <?php echo $student['block_s'] ? 'checked' : ''; ?>> Blocked
                        </label>
                        <label>
                            <input type="radio" name="block_s" value="0" <?php echo !$student['block_s'] ? 'checked' : ''; ?>> Not Blocked
                        </label>
                        <input type="submit" name="update_block_s" value="Update">
                    </form>
                </td>
                <td>
                        <!-- Remove student Form -->
                        <form method="post" action="">
                            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
                            <input type="submit" name="remove_student" value="Remove">
                        </form>
                    </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<h3>Manage Teachers</h3>
<table border="1">
    <thead>
        <tr>
            <th>Teacher ID</th>
            <th>Teacher Name</th>
            <th>Access Status</th>
            <th>Update Access</th>
            <th>Remove Teacher</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($teachers as $teacher) : ?>
            <tr>
                <td><?php echo $teacher['teacher_id']; ?></td>
                <td><?php echo $teacher['teacher_name']; ?></td>
                <td><?php echo $teacher['block_t']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="teacher_id" value="<?php echo $teacher['teacher_id']; ?>">
                        <label>
                            <input type="radio" name="block_t" value="1" <?php echo $teacher['block_t'] ? 'checked' : ''; ?>> Blocked
                        </label>
                        <label>
                            <input type="radio" name="block_t" value="0" <?php echo !$teacher['block_t'] ? 'checked' : ''; ?>> Not Blocked
                        </label>
                        <input type="submit" name="update_block_t" value="Update">
                    </form>
                </td>
                <td>
                        <!-- Remove teacher Form -->
                        <form method="post" action="">
                            <input type="hidden" name="teacher_id" value="<?php echo $teacher['teacher_id']; ?>">
                            <input type="submit" name="remove_teacher" value="Remove">
                        </form>
                    </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <!-- Search Books Section -->
    <h2>Search Books</h2>
    <form method="post" action="">
        <input type="text" name="search_term" placeholder="Search...">
        <input type="submit" value="Search">
    </form>
    <!-- Display Search Results for the teacher -->
    <?php if (isset($search_results) && !empty($search_results)) : ?>
    <h3>Search Results</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Book Name</th>
                <th>Publisher</th>
                <th>Description</th>
                <th>Date Published</th>
                <th>Book URL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($search_results as $book) : ?>
                <tr>
                    <td><?php echo $book['book_id']; ?></td>
                    <td><?php echo $book['book_name']; ?></td>
                    <td><?php echo $book['teacher_name']; ?></td>
                    <td><?php echo $book['description']; ?></td>
                    <td><?php echo $book['date_published']; ?></td>
                    <td><a href="<?php echo $book['book_url']; ?>" target="_blank"><?php echo $book['book_url']; ?></a></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
    <!-- Logout Form -->
    <form method="post" action="">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>
