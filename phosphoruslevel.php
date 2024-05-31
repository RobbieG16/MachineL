<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($link)) {
    include 'config.php';
}
$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice'; // Default to 'rice'
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early'; // Default to 'Early'

$query1 = "SELECT phosphorus FROM rawsensor1 ORDER BY reading_time DESC LIMIT 1";
$query2 = "SELECT phosphorus FROM rawsensor2 ORDER BY reading_time DESC LIMIT 1";
$query3 = "SELECT phosphorus FROM rawsensor3 ORDER BY reading_time DESC LIMIT 1";

$result1 = mysqli_query($link, $query1);
$result2 = mysqli_query($link, $query2);
$result3 = mysqli_query($link, $query3);

$bed1_phosphorus = mysqli_fetch_assoc($result1)['phosphorus'];
$bed2_phosphorus = mysqli_fetch_assoc($result2)['phosphorus'];
$bed3_phosphorus = mysqli_fetch_assoc($result3)['phosphorus'];

$threshold_table = $selected_crop . "_threshold";
$threshold_query = "SELECT phosphorus FROM $threshold_table WHERE stage = '$selected_stage'";
$threshold_result = mysqli_query($link, $threshold_query);
$threshold = mysqli_fetch_assoc($threshold_result)['phosphorus'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phosphorus Level Comparison</title>
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
        #phosphorusChart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="row">
        <div id="phosphorusChart"></div>
    </div>
    <div class="row">
        <div id="phosphorus-alert-box"></div> <!-- Corrected ID for phosphorus alert box -->
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bed1Phosphorus = <?php echo $bed1_phosphorus; ?>;
    const bed2Phosphorus = <?php echo $bed2_phosphorus; ?>;
    const bed3Phosphorus = <?php echo $bed3_phosphorus; ?>;
    const threshold = <?php echo $threshold; ?>;
    
    Highcharts.chart('phosphorusChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Phosphorus Level Comparison'
        },
        xAxis: {
            categories: ['Bed 1', 'Bed 2', 'Bed 3']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Phosphorus Level (mg/kg)'
            }
        },
        tooltip: {
            shared: true
        },
        series: [{
            name: 'Latest Phosphorus Level',
            data: [bed1Phosphorus, bed2Phosphorus, bed3Phosphorus],
            color: '#ff69b4'
        }, {
            name: 'Phosphorus Threshold',
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

    const phosphorusAlertBox = document.getElementById('phosphorus-alert-box');

    const checkPhosphorusLevels = () => {
        let lackingBeds = [];
        if (bed1Phosphorus < threshold) {
            lackingBeds.push('Bed 1');
        }
        if (bed2Phosphorus < threshold) {
            lackingBeds.push('Bed 2');
        }
        if (bed3Phosphorus < threshold) {
            lackingBeds.push('Bed 3');
        }
        if (lackingBeds.length === 0) {
            displayAlert('All beds exhibit adequate phosphorus levels.', 'good');
        } else {
            displayAlert(`Insufficient phosphorus levels were detected in ${lackingBeds.join(', ')}.`, 'bad');
        }
    };

    const displayAlert = (message, type) => {
        const alert = document.createElement('div');
        alert.className = `alert ${type}`;
        alert.textContent = message;
        phosphorusAlertBox.appendChild(alert);
    };

    checkPhosphorusLevels();
});
</script>
</body>
</html>
