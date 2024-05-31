<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($link)) {
    include 'config.php';
}
$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice'; // Default to 'rice'
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early'; // Default to 'Early'

// Get nitrogen levels for each bed from the latest reading
$query1 = "SELECT nitrogen FROM rawsensor1 ORDER BY reading_time DESC LIMIT 1";
$query2 = "SELECT nitrogen FROM rawsensor2 ORDER BY reading_time DESC LIMIT 1";
$query3 = "SELECT nitrogen FROM rawsensor3 ORDER BY reading_time DESC LIMIT 1";

$result1 = mysqli_query($link, $query1);
$result2 = mysqli_query($link, $query2);
$result3 = mysqli_query($link, $query3);

$bed1_nitrogen = mysqli_fetch_assoc($result1)['nitrogen'];
$bed2_nitrogen = mysqli_fetch_assoc($result2)['nitrogen'];
$bed3_nitrogen = mysqli_fetch_assoc($result3)['nitrogen'];

// Determine the correct threshold table based on the selected crop
$threshold_table = $selected_crop . "_threshold";

// Get the nitrogen threshold for the selected crop and stage
$threshold_query = "SELECT nitrogen FROM $threshold_table WHERE stage = '$selected_stage'";
$threshold_result = mysqli_query($link, $threshold_query);
$threshold = mysqli_fetch_assoc($threshold_result)['nitrogen'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nitrogen Level Comparison</title>
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
        #nitrogenChart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div id="nitrogenChart"></div>
    </div>
    <div class="row">
        <div id="nitrogen-alert-box"></div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bed1Nitrogen = <?php echo $bed1_nitrogen; ?>;
    const bed2Nitrogen = <?php echo $bed2_nitrogen; ?>;
    const bed3Nitrogen = <?php echo $bed3_nitrogen; ?>;
    const threshold = <?php echo $threshold; ?>;
    
    Highcharts.chart('nitrogenChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Nitrogen Level Comparison'
        },
        xAxis: {
            categories: ['Bed 1', 'Bed 2', 'Bed 3']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nitrogen Level (mg/kg)'
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Latest Nitrogen Level',
            data: [bed1Nitrogen, bed2Nitrogen, bed3Nitrogen],
            color: '#2ECC71'
        }, {
            name: 'Nitrogen Threshold',
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

    const nitrogenAlertBox = document.getElementById('nitrogen-alert-box');

    const checkNitrogenLevels = () => {
        let lackingBeds = [];
        if (bed1Nitrogen < threshold) {
            lackingBeds.push('Bed 1');
        }
        if (bed2Nitrogen < threshold) {
            lackingBeds.push('Bed 2');
        }
        if (bed3Nitrogen < threshold) {
            lackingBeds.push('Bed 3');
        }
        if (lackingBeds.length === 0) {
            displayAlert('All beds exhibit adequate nitrogen levels.', 'good');
        } else {
            displayAlert(`Insufficient nitrogen levels were detected in ${lackingBeds.join(', ')}.`, 'bad');
        }
    };

    const displayAlert = (message, type) => {
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.textContent = message;
        nitrogenAlertBox.appendChild(alert);
    };

    checkNitrogenLevels();
});
</script>
</body>
</html>
