<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "petrolrequest";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_number = isset($_POST['vehicle_number']) ? trim($_POST['vehicle_number']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $fuel_type = isset($_POST['fuel_type']) ? trim($_POST['fuel_type']) : '';
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
    $payment_type = isset($_POST['payment_type']) ? trim($_POST['payment_type']) : '';

    // Check if any required field is empty
    if (empty($vehicle_number)) {
        die("Error: Vehicle number is required!");
    }
    if (empty($address)) {
        die("Error: Address is required!");
    }
    if (empty($fuel_type)) {
        die("Error: Fuel type is required!");
    }
    if (empty($quantity)) {
        die("Error: Fuel quantity is required!");
    }
    if (empty($payment_type)) {
        die("Error: Payment type is required!");
    }

    // Prepare and insert the data
    $sql = "INSERT INTO fuel_orders (vehicle_number, address, fuel_type, quantity, payment_type) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $vehicle_number, $address, $fuel_type, $quantity, $payment_type);
    
    if ($stmt->execute()) {
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
