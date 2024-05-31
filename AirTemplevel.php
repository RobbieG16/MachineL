<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($link)) {
    include 'config.php';
}
$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice'; // Default to 'rice'
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early'; // Default to 'Early';

// Get air temperature levels for each bed from the latest reading
$query1 = "SELECT air_temp FROM rawsensor1 ORDER BY reading_time DESC LIMIT 1";
$query2 = "SELECT air_temp FROM rawsensor2 ORDER BY reading_time DESC LIMIT 1";
$query3 = "SELECT air_temp FROM rawsensor3 ORDER BY reading_time DESC LIMIT 1";

$result1 = mysqli_query($link, $query1);
$result2 = mysqli_query($link, $query2);
$result3 = mysqli_query($link, $query3);

$bed1_air_temp = mysqli_fetch_assoc($result1)['air_temp'];
$bed2_air_temp = mysqli_fetch_assoc($result2)['air_temp'];
$bed3_air_temp = mysqli_fetch_assoc($result3)['air_temp'];

$threshold_table = $selected_crop . "_threshold";

$threshold_query = "SELECT min_air_temp, air_temp FROM $threshold_table WHERE stage = '$selected_stage'";
$threshold_result = mysqli_query($link, $threshold_query);
$thresholds = mysqli_fetch_assoc($threshold_result);
$min_threshold = $thresholds['min_air_temp'];
$max_threshold = $thresholds['air_temp'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Temperature Level Comparison</title>
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
        #airTempChart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div id="airTempChart"></div>
    </div>
    <div class="row">
        <div id="air-temp-alert-box"></div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bed1AirTemp = <?php echo $bed1_air_temp; ?>;
    const bed2AirTemp = <?php echo $bed2_air_temp; ?>;
    const bed3AirTemp = <?php echo $bed3_air_temp; ?>;
    const minThreshold = <?php echo $min_threshold; ?>;
    const maxThreshold = <?php echo $max_threshold; ?>;
    
    Highcharts.chart('airTempChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Air Temperature Level Comparison'
        },
        xAxis: {
            categories: ['Bed 1', 'Bed 2', 'Bed 3']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Air Temperature (°C)'
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Latest Air Temperature',
            data: [bed1AirTemp, bed2AirTemp, bed3AirTemp],
            color: '#E74C3C'
        }, {
            name: 'Minimum Air Temperature Threshold',
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
            name: 'Maximum Air Temperature Threshold',
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

    const airTempAlertBox = document.getElementById('air-temp-alert-box');

    const checkAirTempLevels = () => {
        let outOfRangeBeds = [];
        if (bed1AirTemp < minThreshold) {
            outOfRangeBeds.push('Bed 1');
        }
        if (bed2AirTemp < minThreshold) {
            outOfRangeBeds.push('Bed 2');
        }
        if (bed3AirTemp < minThreshold) {
            outOfRangeBeds.push('Bed 3');
        }
        if (outOfRangeBeds.length === 0) {
            displayAlert('All beds exhibit adequate air temperature levels.', 'good');
        } else {
            displayAlert(`Insufficient air temperature levels detected in ${outOfRangeBeds.join(', ')}.`, 'bad');
        }
    };

    const displayAlert = (message, type) => {
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.textContent = message;
        airTempAlertBox.appendChild(alert);
    };

    checkAirTempLevels();
});
</script>
</body>
</html>
