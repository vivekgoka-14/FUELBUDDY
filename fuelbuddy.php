<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require "db_connect.php";

// Check request type
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Fetch fuel stations
    $location = isset($_GET["location"]) ? $_GET["location"] : "";
    
    if (!empty($location)) {
        $stmt = $conn->prepare("SELECT * FROM fuel_stations WHERE location = ?");
        $stmt->bind_param("s", $location);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stations = [];
        while ($row = $result->fetch_assoc()) {
            $stations[] = $row;
        }
        
        echo json_encode($stations);
        $stmt->close();
    } else {
        echo json_encode(["error" => "Location not provided"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle booking
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data["user_name"], $data["fuel_station_id"])) {
        $stmt = $conn->prepare("INSERT INTO bookings (user_name, fuel_station_id) VALUES (?, ?)");
        $stmt->bind_param("si", $data["user_name"], $data["fuel_station_id"]);
        if ($stmt->execute()) {
            echo json_encode(["success" => "Booking confirmed"]);
        } else {
            echo json_encode(["error" => "Booking failed"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Missing required fields"]);
    }
}

$conn->close();
?>
