<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cropName']) && isset($_POST['selectedMonths'])) {
        $cropName = $_POST['cropName'];
        $selectedMonths = json_decode($_POST['selectedMonths']);

        // Query to check if selected months are already assigned to another crop
        $sql = "SELECT COUNT(*) AS count FROM threshold WHERE crop_name != '$cropName' AND FIND_IN_SET(months, '" . implode(',', $selectedMonths) . "') > 0";

        // Execute the query
        $result = mysqli_query($link, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $conflict = ($row['count'] > 0) ? true : false;
            echo json_encode(['conflict' => $conflict]);
        } else {
            echo json_encode(['error' => 'Database query error']);
        }
    } else {
        echo json_encode(['error' => 'Missing POST data']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
