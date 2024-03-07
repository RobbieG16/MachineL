<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esp_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the month and year parameters are set, otherwise, use the current date
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
$currentYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

$query = $conn->prepare("SELECT DAY(reading_date) as day, MONTH(reading_date) as month, YEAR(reading_date) as year, status FROM overall_data WHERE MONTH(reading_date) = ? AND YEAR(reading_date) = ?");
$query->bind_param("ss", $currentMonth, $currentYear);

if ($query->execute()) {
    $result = $query->get_result();

    $statusData = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $day = $row['day'];
            $month = $row['month'];
            $year = $row['year'];
            $status = $row['status'];
    
            // Determine if the day is clickable based on the status
            $clickable = ($status == 'Green') ? true : false;
    
            // Add day data to the statusData array only if it doesn't exist or if it's a 'Green' status (to prioritize 'Green' over 'Red')
            if (!isset($statusData[$day]) || $status == 'Green') {
                $statusData[$day] = array('month' => $month, 'year' => $year, 'status' => $status, 'clickable' => $clickable);
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($statusData);
} else {
    die("Query execution failed: " . $query->error);
}

$query->close();
$conn->close();
?>