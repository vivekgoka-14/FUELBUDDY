<?php
$servername = "localhost";
$username = "root";  // Change if using a different username
$password = "";  // Change if there's a password
$dbname = "mechanic_service";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_number = $_POST['vehicle_number'];
    $address = $_POST['address'];
    $fuel_type = $_POST['fuel_type'];
    $problem_type = $_POST['problem_type'];
    $payment_type = $_POST['payment_type'];

    // Insert into database
    $sql = "INSERT INTO service_requests (vehicle_number, address, fuel_type, problem_type, payment_type) 
            VALUES ('$vehicle_number', '$address', '$fuel_type', '$problem_type', '$payment_type')";

    if ($conn->query($sql) === TRUE) {
        echo "Service request submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
