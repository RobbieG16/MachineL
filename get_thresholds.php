<?php
include 'config.php';

function fetchThresholdsFromDatabase($link) {
    $thresholds = array();

    $query = "SELECT * FROM threshold_months WHERE crop_name IN ('Rice', 'Corn')";
    $link = mysqli_query($link, $query);

    if ($link) {
        while ($row = mysqli_fetch_assoc($link)) {
            $months = explode(',', $row['months']);
            $thresholds[] = array(
                "crop_name" => $row['crop_name'],
                "months" => $months
            );
        }
        mysqli_free_result($link);
    } else {
        
        echo json_encode(array("error" => "Error: " . mysqli_error($link)));
        exit; 
    }

    return $thresholds;
}

$thresholds = fetchThresholdsFromDatabase($link);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

echo json_encode($thresholds);
?>
