<?php
// Database connection settings for XAMPP
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
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $mobile = trim($_POST["mobile"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($username) || empty($email) || empty($mobile) || empty($password)) {
        echo "All fields are required!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL statement
        $sql = "INSERT INTO nithin (username, email, mobile, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $mobile, $hashed_password);

        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: login.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
