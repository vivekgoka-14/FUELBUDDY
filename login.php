<?php
session_start(); // Start session for user authentication

// Database connection settings
$host = "localhost";
$dbname = "project"; // Replace with your actual database name
$dbusername = "root"; // Default username for XAMPP MySQL
$dbpassword = ""; // Default password for XAMPP MySQL (leave empty if not set)

// Create a connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password exist
    if (!isset($_POST["username"], $_POST["password"])) {
        die("<script>alert('Invalid form submission!'); window.location.href='login.html';</script>");
    }

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($username) || empty($password)) {
        die("<script>alert('Username and Password are required!'); window.location.href='login.html';</script>");
    }

    // Check if the table exists
    $table_check = "SHOW TABLES LIKE 'nithin'";
    $result = $conn->query($table_check);
    if ($result->num_rows == 0) {
        die("Error: Table 'nithin' does not exist in database '$dbname'.");
    }

    // Prepare SQL statement
    $sql = "SELECT  username, password FROM nithin WHERE username = ?";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result( $db_username, $db_password);
        $stmt->fetch();

        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $db_password)) {
            // Password is correct, start session
            // $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $db_username;

            // Show success message and redirect to home.html
            echo "<script>alert('Login Successful! Redirecting to home page...');</script>";
            echo "<meta http-equiv='refresh' content='2;url=home.html'>"; // Redirect in 2 seconds
            exit();
        } else {
            die("<script>alert('Invalid username or password!'); window.location.href='login.html';</script>");
        }
    } else {
        die("<script>alert('User not found!'); window.location.href='login.html';</script>");
    }

    $stmt->close();
}

$conn->close();
?>
