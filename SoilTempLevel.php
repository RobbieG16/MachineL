<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($link)) {
    include 'config.php';
}
$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice'; // Default to 'rice'
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early'; // Default to 'Early';

// Get soil temperature levels for each bed from the latest reading
$query1 = "SELECT soil_temp FROM rawsensor1 ORDER BY reading_time DESC LIMIT 1";
$query2 = "SELECT soil_temp FROM rawsensor2 ORDER BY reading_time DESC LIMIT 1";
$query3 = "SELECT soil_temp FROM rawsensor3 ORDER BY reading_time DESC LIMIT 1";

$result1 = mysqli_query($link, $query1);
$result2 = mysqli_query($link, $query2);
$result3 = mysqli_query($link, $query3);

$bed1_soil_temp = mysqli_fetch_assoc($result1)['soil_temp'];
$bed2_soil_temp = mysqli_fetch_assoc($result2)['soil_temp'];
$bed3_soil_temp = mysqli_fetch_assoc($result3)['soil_temp'];

// Determine the correct threshold table based on the selected crop
$threshold_table = $selected_crop . "_threshold";

// Get the soil temperature threshold for the selected crop and stage
$threshold_query = "SELECT soil_temp FROM $threshold_table WHERE stage = '$selected_stage'";
$threshold_result = mysqli_query($link, $threshold_query);
$threshold = mysqli_fetch_assoc($threshold_result);
$threshold_value = $threshold['soil_temp'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil Temperature Level Comparison</title>
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
        #soilTempChart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div id="soilTempChart"></div>
    </div>
    <div class="row">
        <div id="soil-temp-alert-box"></div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bed1SoilTemp = <?php echo $bed1_soil_temp; ?>;
    const bed2SoilTemp = <?php echo $bed2_soil_temp; ?>;
    const bed3SoilTemp = <?php echo $bed3_soil_temp; ?>;
    const thresholdValue = <?php echo $threshold_value; ?>;
    
    Highcharts.chart('soilTempChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Soil Temperature Level Comparison'
        },
        xAxis: {
            categories: ['Bed 1', 'Bed 2', 'Bed 3']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Soil Temperature (°C)'
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Latest Soil Temperature',
            data: [bed1SoilTemp, bed2SoilTemp, bed3SoilTemp],
            color: '#662b04'
        }, {
            name: 'Soil Temperature Threshold',
            data: [thresholdValue, thresholdValue, thresholdValue],
            type: 'line',
            color: 'red',
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
            }
        }]
    });

    const soilTempAlertBox = document.getElementById('soil-temp-alert-box');

    const checkSoilTempLevels = () => {
        let lackingBeds = [];
        if (bed1SoilTemp < thresholdValue) {
            lackingBeds.push('Bed 1');
        }
        if (bed2SoilTemp < thresholdValue) {
            lackingBeds.push('Bed 2');
        }
        if (bed3SoilTemp < thresholdValue) {
            lackingBeds.push('Bed 3');
        }
        if (lackingBeds.length === 0) {
            displayAlert('All beds exhibit adequate soil temperature levels.', 'good');
        } else {
            displayAlert(`Insufficient soil temperature levels were detected in ${lackingBeds.join(', ')}.`, 'bad');
        }
    };

    const displayAlert = (message, type) => {
        const alert = document.createElement('div');
        alert
        .className = `alert ${type}`;
        alert.textContent = message;
        soilTempAlertBox.appendChild(alert);
    };

    checkSoilTempLevels();
});
</script>
</body>
</html>
