<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['sensor'])) {
    $selectedSensor = $_GET['sensor'];
    $tableName = "raw" . $selectedSensor;

    $query = "SELECT * FROM $tableName ORDER BY reading_time DESC LIMIT 3";
    $result = mysqli_query($link, $query);
    
    // Output table headers
    echo "<tr>";
    echo "<th>Timestamps</th>";
    echo "<th>Nitrogen</th>";
    echo "<th>Potassium</th>";
    echo "<th>Phosphorus</th>";
    echo "<th>Soil Temperature</th>";
    echo "<th>Air Temperature</th>";
    echo "<th>Soil Moisture</th>";
    echo "</tr>";
    
    // Output data rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['reading_time']}</td>";
        echo "<td>{$row['nitrogen']}</td>";
        echo "<td>{$row['potassium']}</td>";
        echo "<td>{$row['phosphorus']}</td>";
        echo "<td>{$row['soil_temp']}</td>";
        echo "<td>{$row['air_temp']}</td>";
        echo "<td>{$row['soil_moisture']}</td>";
        echo "</tr>";
    }
}
?>
