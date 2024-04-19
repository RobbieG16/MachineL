<?php
// Function to generate nutrient-specs structure
function generateNutrientSpecs($nutrient, $sensorData, $averages) {
    ?>
    <!-- <?php echo strtoupper($nutrient); ?> -->
    <div class="row sensor-specs" id="<?php echo $nutrient; ?>-specs">
        <div class="col value-time">
            <div class="row title">
                <h4><?php echo ucfirst($nutrient); ?></h4>
            </div>
            <div class="row sensor">
                <?php foreach ($sensorData as $sensorId => $sensorDataArray): ?>
                    <div class="col">
                        <h6 class="bed-title">Bed <?php echo $sensorId; ?></h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th class="valuee" scope="col">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sensorDataArray as $data): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($data['reading_time']))); ?></td>
                                        <td class="values"><?php echo htmlspecialchars($data[$nutrient]); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col trends-insights">
            <div class="row trends">
                <div class="title">
                    <h4>Trends</h4>
                </div>
                    <div id="<?php echo $nutrient; ?>Chart" style="height: 400px;" class="chart-container"></div>
                    <div class="col text-center mt-2">
                        <div class="row"><h5>Last Week Average</h5></div>
                        <h5 class="row"><span id="lastWeekAverage-<?php echo strtolower($nutrient); ?>"><?php echo number_format($averages[$nutrient]['lastWeek'], 2); ?></span></h5>
                    </div>
                    <div class="col text-center mt-2">
                        <div class="row"><h5>Last Month Average</h5></div>
                        <h5 class="row"><span id="lastMonthAverage-<?php echo strtolower($nutrient); ?>"><?php echo number_format($averages[$nutrient]['lastMonth'], 2); ?></span></h5>
                    </div>
                    <!-- <h5 class="text-center mt-3">Last Week Average: <span id="lastWeekAverage-<?php echo strtolower($nutrient); ?>"><?php echo number_format($averages[$nutrient]['lastWeek'], 2); ?></span> || Last Month Average: <span id="lastMonthAverage-<?php echo strtolower($nutrient); ?>"><?php echo number_format($averages[$nutrient]['lastMonth'], 2); ?></span></h5> -->

                </div>
            <!-- <div class="row insights">
                <h4>Insights</h4>
                <div class="row">
                </div>
            </div> -->
        </div>
    </div>


    
    <?php

    
}

require_once 'config.php';
$reading_times1 = array();
$reading_times2 = array();
$reading_times3 = array();

$air_temp = array();
$soil_temp = array();
$nitrogen = array();
$phosphorus = array();
$potassium = array();
$soil_moisture = array();

$query = "SELECT reading_time, air_temp, soil_temp, soil_moisture, nitrogen, phosphorus, potassium FROM rawsensor1 ORDER BY reading_time";
$result = $link->query($query);

$query2 = "SELECT reading_time, air_temp, soil_temp, soil_moisture, nitrogen, phosphorus, potassium FROM rawsensor2 ORDER BY reading_time";
$result2 = $link->query($query2);

$query3 = "SELECT reading_time, air_temp, soil_temp, soil_moisture, nitrogen, phosphorus, potassium FROM rawsensor3 ORDER BY reading_time";
$result3 = $link->query($query3);


if ($result) {
    while ($row = $result->fetch_assoc()) {
        // echo "Data from result:\n";
        // var_dump($row);
        //pang debug lang yan talaga mga par hehe
        $reading_times1[] = $row['reading_time'];
        $air_temp[] = $row['air_temp'];
        $soil_temp[] = $row['soil_temp'];
        $nitrogen[] = $row['nitrogen'];
        $phosphorus[] = $row['phosphorus'];
        $potassium[] = $row['potassium'];
        $soil_moisture[] = $row['soil_moisture'];
    }
    $result->free();
}
// Fetch data from the second table
if ($result2) {
    while ($row = $result2->fetch_assoc()) {
        $reading_times2[] = $row['reading_time'];
        $air_temp2[] = $row['air_temp'];
        $soil_temp2[] = $row['soil_temp'];
        $nitrogen2[] = $row['nitrogen'];
        $phosphorus2[] = $row['phosphorus'];
        $potassium2[] = $row['potassium'];
        $soil_moisture2[] = $row['soil_moisture'];
    }
    $result2->free();
}
if ($result3) {
    while ($row = $result3->fetch_assoc()) {
        $reading_times3[] = $row['reading_time'];
        $air_temp3[] = $row['air_temp'];
        $soil_temp3[] = $row['soil_temp'];
        $nitrogen3[] = $row['nitrogen'];
        $phosphorus3[] = $row['phosphorus'];
        $potassium3[] = $row['potassium'];
        $soil_moisture3[] = $row['soil_moisture'];
    }
    $result3->free();
}


function getLatestSensorData() {
    global $link;
    $allSensorData = [];

    for ($sensorId = 1; $sensorId <= 3; $sensorId++) {
        $tableName = "rawsensor{$sensorId}";
        $sql = "SELECT * FROM $tableName ORDER BY reading_time DESC LIMIT 9";
        $result = mysqli_query($link, $sql);

        $sensorData = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $sensorData[] = $row;
            }
        }

        $allSensorData[$sensorId] = $sensorData;
    }

    return $allSensorData;
}

// Get the data for all sensors
$SensorData = getLatestSensorData();
function getAverage($table, $column, $startDate, $endDate) {
    global $link;
    $sql = "SELECT AVG($column) AS average FROM $table WHERE reading_time BETWEEN '$startDate' AND '$endDate'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['average'];
    } else {
        return 0;
    }
}

$columns = ['air_temp', 'soil_temp', 'soil_moisture', 'nitrogen', 'phosphorus', 'potassium'];

$lastWeekStartDate = date('Y-m-d', strtotime('-1 week'));
$lastWeekEndDate = date('Y-m-d');

$lastMonthStartDate = date('Y-m-d', strtotime('-1 month'));
$lastMonthEndDate = date('Y-m-d');

$averages = array();

foreach ($columns as $column) {
    $table_averages = array();
    for ($sensor = 1; $sensor <= 3; $sensor++) {
        $sensorName = 'rawsensor' . $sensor;
        $averageLastWeek = getAverage($sensorName, $column, $lastWeekStartDate, $lastWeekEndDate);
        $averageLastMonth = getAverage($sensorName, $column, $lastMonthStartDate, $lastMonthEndDate);

        $table_averages[$sensorName] = array('lastWeek' => $averageLastWeek, 'lastMonth' => $averageLastMonth);
    }

    $lastWeekAverages = array_column($table_averages, 'lastWeek');
    $lastMonthAverages = array_column($table_averages, 'lastMonth');
    $averageLastWeek = array_sum($lastWeekAverages) / count($lastWeekAverages);
    $averageLastMonth = array_sum($lastMonthAverages) / count($lastMonthAverages);

    $averages[$column] = array('lastWeek' => $averageLastWeek, 'lastMonth' => $averageLastMonth);
}
$link->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="stylesheet" href="./css/analytical.css">

  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
    
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h2 class="mb-4">Analytics</h2>
        <div class="container-fluid">
            <div class="row pick-nutrient">
                <div class="btn-group pick-nutrient custom-btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="nitrogen" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="nitrogen">Nitrogen</label>

                    <input type="radio" class="btn-check" name="btnradio" id="phosphorus" autocomplete="off">
                    <label class="btn btn-outline-primary" for="phosphorus">Phosphorus</label>

                    <input type="radio" class="btn-check" name="btnradio" id="potassium" autocomplete="off">
                    <label class="btn btn-outline-primary" for="potassium">Potassium</label>
                    
                    <input type="radio" class="btn-check" name="btnradio" id="air_temp" autocomplete="off">
                    <label class="btn btn-outline-primary" for="air_temp">Air Temperature</label>

                    <input type="radio" class="btn-check" name="btnradio" id="soil_temp" autocomplete="off">
                    <label class="btn btn-outline-primary" for="soil_temp">Soil Temperature</label>

                    <input type="radio" class="btn-check" name="btnradio" id="soil_moisture" autocomplete="off">
                    <label class="btn btn-outline-primary" for="soil_moisture">Soil Moisture</label>
                </div>
                
            </div>

            <?php
            // List of nutrients
            $nutrients = ['nitrogen', 'phosphorus', 'potassium', 'air_temp', 'soil_temp', 'soil_moisture'];
            
            // Loop through nutrients and generate nutrient-specs structure
            foreach ($nutrients as $nutrient) {
                generateNutrientSpecs($nutrient, $SensorData, $averages);
            }
            ?>

        
            <script>
                // Pass PHP arrays to JavaScript for chart generation
                var air_tempData = <?php echo json_encode($air_temp); ?>;
                var soil_tempData = <?php echo json_encode($soil_temp); ?>;
                var nitrogenData = <?php echo json_encode($nitrogen); ?>;
                var phosphorusData = <?php echo json_encode($phosphorus); ?>;
                var potassiumData = <?php echo json_encode($potassium); ?>;
                var soil_moistureData = <?php echo json_encode($soil_moisture); ?>;
                var reading_times1 = <?php echo json_encode($reading_times1);?>;
                var reading_times2 = <?php echo json_encode($reading_times2);?>;
                var reading_times3 = <?php echo json_encode($reading_times3);?>;
                console.log("Reading Times 1:");
                console.log(reading_times1);
                console.log("Reading Times 2:");    
                console.log(reading_times2);
                console.log("Reading Times 3:");
                console.log(reading_times3);


                function extractDate(timestamp) {
                    var parts = timestamp.split(' ')[0].split('-');
                    var year = parseInt(parts[0]);
                    var month = parseInt(parts[1]) - 1; 
                    var day = parseInt(parts[2]);
                    
                    var date = new Date(year, month, day);
                    
                    return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
                }

                <?php foreach ($nutrients as $nutrient) : ?>
                    console.log('X-axis categories for <?php echo $nutrient;?> chart:');
                    console.log(reading_times1.map(timestamp => extractDate(timestamp))); 

                    Highcharts.chart('<?php echo $nutrient; ?>Chart', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: '<?php echo ucfirst($nutrient); ?> Over Time'
                },
                xAxis: {
                    categories: reading_times1.map(extractDate), 
                    crosshair: true
                },
                yAxis: {
                    title: {
                        text: '<?php echo ($nutrient === "air_temp" || $nutrient === "soil_temp" || $nutrient === "soil_moisture") ? "Temperature (°C)" : "Values"; ?>'
                    }
                },
                series: [
                    {
                        name: '(Bed 1)',
                        data: <?php echo "{$nutrient}Data"; ?>.map(Number)
                    },
                    {
                        name: '(Bed 2)',
                        data: <?php echo json_encode(${$nutrient.'2'}); ?>.map(Number)
                    },
                    {
                        name: '(Bed 3)',
                        data: <?php echo json_encode(${$nutrient.'3'}); ?>.map(Number)
                    }

                ]
            });
                <?php endforeach; ?>
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var nutrientRadios = document.querySelectorAll('.pick-nutrient .btn-check');
                    var sensorSpecs = document.querySelectorAll('.sensor-specs');

                    sensorSpecs.forEach(function (spec) {
                        spec.style.display = 'none';
                    });

                    var initialNutrient = 'nitrogen';
                    document.getElementById(initialNutrient + '-specs').style.display = '';

                    nutrientRadios.forEach(function (radio) {
                        radio.addEventListener('change', function () {
                            sensorSpecs.forEach(function (spec) {
                                spec.style.display = 'none';
                            });

                            var selectedNutrient = document.querySelector('.pick-nutrient .btn-check:checked').id;
                            var selectedSpecs = document.getElementById(selectedNutrient + '-specs');
                            if (selectedSpecs) {
                                selectedSpecs.style.display = '';
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
