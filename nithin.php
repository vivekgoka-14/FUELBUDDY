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
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($username) || empty($password)) {
        echo "Username and Password are required!";
    } else {
        // Prepare SQL statement to fetch user data
        $sql = "SELECT id, username, password FROM nithin WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if user exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $db_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $db_password)) {
                // Password is correct, start sessiona
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $db_username;

                // Redirect to a dashboard or welcome page
                header("Location: home.html");
                exit();
            } else {
                echo "Invalid username or password!";
            }
        } else {
            echo "User not found!";
        }

        // Close statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
