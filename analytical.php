<?php
// Function to generate nutrient-specs structure
function generateNutrientSpecs($nutrient, $sensorData) {
    ?>
    <!-- <?php echo strtoupper($nutrient); ?> -->
    <div class="row sensor-specs" id="<?php echo $nutrient; ?>-specs">
        <div class="col value-time">
            <div class="row title">
                <h4><?php echo ucfirst($nutrient); ?></h4>
            </div>
            <div class="row sensor">
                <?php foreach ($sensorData as $sensorId => $sensorDataArray): ?>
                    <h6>Sensor <?php echo $sensorId; ?></h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Timestamp</th>
                                <th class="valuee" scope="col">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sensorDataArray as $data): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($data['reading_time']); ?></td>
                                    <td class="values"><?php echo htmlspecialchars($data[$nutrient]); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col trends-insights">
            <div class="row trends">
                <div class="title">
                    <h4>Trends</h4>
                </div>
                    <div id="<?php echo $nutrient; ?>Chart" style="height: 400px;" class="chart-container"></div>
                </div>
            <div class="row insights">
                <h4>Insights</h4>
                <div class="row">
                    <!-- Add insights content if needed -->
                </div>
            </div>
        </div>
    </div>
    <?php
}

require_once 'config.php';

$air_temp = array();
$soil_temp = array();
$nitrogen = array();
$phosphorus = array();
$potassium = array();
$soil_moisture = array();

$query = "SELECT air_temp, soil_temp, soil_moisture, nitrogen, phosphorus, potassium FROM rawsensor1";
$result = $link->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $air_temp[] = $row['air_temp'];
        $soil_temp[] = $row['soil_temp'];
        $nitrogen[] = $row['nitrogen'];
        $phosphorus[] = $row['phosphorus'];
        $potassium[] = $row['potassium'];
        $soil_moisture[] = $row['soil_moisture'];
    }
    $result->free();
}

function getLatestSensorData() {
    global $link;
    $allSensorData = [];

    for ($sensorId = 1; $sensorId <= 3; $sensorId++) {
        $tableName = "rawsensor{$sensorId}";
        $sql = "SELECT * FROM `$tableName` ORDER BY `reading_time` DESC LIMIT 8";
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
$link->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analyticals</title>
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
                <div class="btn-group pick-nutrient" role="group" aria-label="Basic radio toggle button group">
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
                generateNutrientSpecs($nutrient, $SensorData);
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

                // Function to extract date from timestamp
                function extractDate(timestamp) {
                    return new Date(timestamp * 1000).toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
                }
                // Loop through nutrients and generate Highcharts charts
                <?php foreach ($nutrients as $nutrient) : ?>
                    Highcharts.chart('<?php echo $nutrient; ?>Chart', {
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: '<?php echo ucfirst($nutrient); ?> Over Time'
                        },
                        xAxis: {
                            categories: air_tempData.map(extractDate), // Replace air_tempData with the appropriate data array
                            crosshair: true
                        },
                        yAxis: {
                            title: {
                                text: '<?php echo ($nutrient === "air_temp" || $nutrient === "soil_temp" || $nutrient === "soil_moisture") ? "Temperature (°C)" : "Values"; ?>'
                            }
                        },
                        series: [{
                            name: '<?php echo ucfirst($nutrient); ?>',
                            data: <?php echo "{$nutrient}Data"; ?>.map(Number) // Ensure data is in Number format
                        }]
                    });
                <?php endforeach; ?>
            </script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var nutrientRadios = document.querySelectorAll('.pick-nutrient .btn-check');
                    var sensorSpecs = document.querySelectorAll('.sensor-specs');

                    // Hide all sensor-specs sections initially
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
