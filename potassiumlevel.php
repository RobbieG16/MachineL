<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($link)) {
    include 'config.php';
}
$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice'; // Default to 'rice'
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early'; // Default to 'Early'

$query1 = "SELECT potassium FROM rawsensor1 ORDER BY reading_time DESC LIMIT 1";
$query2 = "SELECT potassium FROM rawsensor2 ORDER BY reading_time DESC LIMIT 1";
$query3 = "SELECT potassium FROM rawsensor3 ORDER BY reading_time DESC LIMIT 1";

$result1 = mysqli_query($link, $query1);
$result2 = mysqli_query($link, $query2);
$result3 = mysqli_query($link, $query3);

$bed1_potassium = mysqli_fetch_assoc($result1)['potassium'];
$bed2_potassium = mysqli_fetch_assoc($result2)['potassium'];
$bed3_potassium = mysqli_fetch_assoc($result3)['potassium'];

$threshold_table = $selected_crop . "_threshold";
$threshold_query = "SELECT potassium FROM $threshold_table WHERE stage = '$selected_stage'";
$threshold_result = mysqli_query($link, $threshold_query);
$threshold = mysqli_fetch_assoc($threshold_result)['potassium'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potassium Level Comparison</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
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
        #potassiumChart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div id="potassiumChart"></div>
    </div>
    <div class="row">
        <div id="potassium-alert-box"></div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bed1Potassium = <?php echo $bed1_potassium; ?>;
    const bed2Potassium = <?php echo $bed2_potassium; ?>;
    const bed3Potassium = <?php echo $bed3_potassium; ?>;
    const threshold = <?php echo $threshold; ?>;
    
    Highcharts.chart('potassiumChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Potassium Level Comparison'
        },
        xAxis: {
            categories: ['Bed 1', 'Bed 2', 'Bed 3']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Potassium Level (mg/kg)'
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Latest Potassium Level',
            data: [bed1Potassium, bed2Potassium, bed3Potassium],
            color: '#8e44ad'
        }, {
            name: 'Potassium Threshold',
            data: [threshold, threshold, threshold],
            type: 'line',
            color: 'forestgreen',
            marker: {
                enabled: false
            },
            dashStyle: 'shortdot',
            tooltip: {
                pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>'
            }
        }]
    });

    const potassiumAlertBox = document.getElementById('potassium-alert-box');

    const checkPotassiumLevels = () => {
        let lackingBeds = [];
        if (bed1Potassium < threshold) {
            lackingBeds.push('Bed 1');
        }
        if (bed2Potassium < threshold) {
            lackingBeds.push('Bed 2');
        }
        if (bed3Potassium < threshold) {
            lackingBeds.push('Bed 3');
        }
        if (lackingBeds.length === 0) {
            displayAlert('All beds exhibit adequate potassium levels.', 'good');
        } else {
            displayAlert(`Insufficient potassium levels were detected in ${lackingBeds.join(', ')}.`, 'bad');
        }
    };

    const displayAlert = (message, type) => {
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.textContent = message;
        potassiumAlertBox.appendChild(alert);
    };

    checkPotassiumLevels();
});
</script>
</body>
</html>
