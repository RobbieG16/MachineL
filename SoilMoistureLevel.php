<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($link)) {
    include 'config.php';
}
$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice'; // Default to 'rice'
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early'; // Default to 'Early';

// Get soil moisture levels for each bed from the latest reading
$query1 = "SELECT soil_moisture FROM rawsensor1 ORDER BY reading_time DESC LIMIT 1";
$query2 = "SELECT soil_moisture FROM rawsensor2 ORDER BY reading_time DESC LIMIT 1";
$query3 = "SELECT soil_moisture FROM rawsensor3 ORDER BY reading_time DESC LIMIT 1";

$result1 = mysqli_query($link, $query1);
$result2 = mysqli_query($link, $query2);
$result3 = mysqli_query($link, $query3);

$bed1_soil_moisture = mysqli_fetch_assoc($result1)['soil_moisture'];
$bed2_soil_moisture = mysqli_fetch_assoc($result2)['soil_moisture'];
$bed3_soil_moisture = mysqli_fetch_assoc($result3)['soil_moisture'];

// Determine the correct threshold table based on the selected crop
$threshold_table = $selected_crop . "_threshold";

// Get the soil moisture threshold for the selected crop and stage
$threshold_query = "SELECT min_soil_moisture, soil_moisture FROM $threshold_table WHERE stage = '$selected_stage'";
$threshold_result = mysqli_query($link, $threshold_query);
$thresholds = mysqli_fetch_assoc($threshold_result);
$min_threshold = $thresholds['min_soil_moisture'];
$max_threshold = $thresholds['soil_moisture'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil Moisture Level Comparison</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/highcharts-more.js"></script>
    <style>
        .alert {
            padding: 10px;
            border: 1px solid;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .good {
            background-color: #C6F7D0;
            border-color: #34C759;
        }
        .bad {
            background-color: #FFD7BE;
            border-color: #FFC107;
        }
        #soilMoistureChart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div id="soilMoistureChart"></div>
    </div>
    <div class="row">
        <div id="soil-moisture-alert-box"></div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bed1SoilMoisture = <?php echo $bed1_soil_moisture; ?>;
    const bed2SoilMoisture = <?php echo $bed2_soil_moisture; ?>;
    const bed3SoilMoisture = <?php echo $bed3_soil_moisture; ?>;
    const minThreshold = <?php echo $min_threshold; ?>;
    const maxThreshold = <?php echo $max_threshold; ?>;
    
    Highcharts.chart('soilMoistureChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Soil Moisture Level Comparison'
        },
        xAxis: {
            categories: ['Bed 1', 'Bed 2', 'Bed 3']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Soil Moisture Level (%)'
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Latest Soil Moisture Level',
            data: [bed1SoilMoisture, bed2SoilMoisture, bed3SoilMoisture],
            color: '#3498DB'
        }, {
            name: 'Minimum Soil Moisture Threshold',
            data: [minThreshold, minThreshold, minThreshold],
            type: 'line',
            color: 'red',
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
            }
        }, {
           
            name: 'Maximum Soil Moisture Threshold',
        data: [maxThreshold, maxThreshold, maxThreshold],
        type: 'line',
        color: 'blue',
        marker: {
            enabled: false
        },
        dashStyle: 'shortdot',
        tooltip: {
            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
        }
    }]
});

    const soilMoistureAlertBox = document.getElementById('soil-moisture-alert-box');

    const checkSoilMoistureLevels = () => {
        let outOfRangeBeds = [];
        if (bed1SoilMoisture < minThreshold) {
            outOfRangeBeds.push('Bed 1');
        }
        if (bed2SoilMoisture < minThreshold) {
            outOfRangeBeds.push('Bed 2');
        }
        if (bed3SoilMoisture < minThreshold) {
            outOfRangeBeds.push('Bed 3');
        }
        if (outOfRangeBeds.length === 0) {
            displayAlert('All beds exhibit sufficient soil moisture levels.', 'good');
        } else {
            displayAlert(`Soil moisture levels are insufficient in ${outOfRangeBeds.join(', ')}.`, 'bad');
        }
    };

    const displayAlert = (message, type) => {
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.textContent = message;
        soilMoistureAlertBox.appendChild(alert);
    };

    checkSoilMoistureLevels();
    });
    </script>
    </body>
    </html>
