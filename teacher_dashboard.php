<?php
// Start session
session_start();

// Include your database connection file
include('connection.php');

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle book publication
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['publish_book'])) {
    // Process book publication by the teacher
    $book_name = $_POST['book_name'];
    $publisher = $_POST['publisher'];
    $description = $_POST['description'];
    $book_url = $_POST['book_url']; // New line to get book_url
    
    // Insert the book details into the database 
    $insert_query = "INSERT INTO book (book_name, publisher, date_published, description, book_url, book_request) 
                     VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?, false)";
    
    // Create a prepared statement
    $stmt = mysqli_prepare($connection, $insert_query);

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "ssss", $book_name, $publisher, $description, $book_url);

    if (mysqli_stmt_execute($stmt)) {
        echo "Book published successfully";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_term'])) {
    // Handle book search
    $search_term = mysqli_real_escape_string($connection, $_POST['search_term']);
    
    
    $sql = "SELECT Book.book_id, Book.book_name, Book.description, Book.date_published, Teacher.teacher_name, Book.book_url
            FROM Book
            LEFT JOIN Teacher ON Book.publisher = Teacher.teacher_id
            WHERE (Book.book_id LIKE '%$search_term%' 
            OR Book.book_name LIKE '%$search_term%' 
            OR Teacher.teacher_name LIKE '%$search_term%')
            AND book_request = 1";
    
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error in SQL query: " . mysqli_error($connection));
    } else {
        
    }

    // Fetch the search results for the teacher
    $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
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
        form {
            margin-bottom: 15px;
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
            width: 200px;
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
    <h1>Welcome, Teacher!</h1>
    
    <!-- Publish New Book Section -->
    <h2>Publish New Book</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="container">
        <!-- Book details form -->
        <input type="text" name="book_name" placeholder="Book Name" required><br>
        <input type="text" name="publisher" placeholder="Publisher" required><br>
        <textarea name="description" placeholder="Description"></textarea><br>
        <input type="text" name="book_url" placeholder="Book URL" required><br> 
        <input type="submit" name="publish_book" value="Add Book">
    </form>
    
    <!-- Search Books Section -->
    <h2>Search Books</h2>
    <form method="post" action="" class="container">
        <input type="text" name="search_term" placeholder="Search...">
        <input type="submit" value="Search" class="search-button">
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
    <form method="post" action="" class="logout-form">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>
