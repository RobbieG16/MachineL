<?php
// Include the database configuration file
require_once 'config.php';

// Initialize arrays to hold the data for each column
$all_air_temp = array();
$all_soil_temperature = array();
$all_nitrogen = array();
$all_phosphorus = array();
$all_potassium = array();

// Query the database to get the data for all columns
$query = "SELECT all_air_temp, all_soil_temperature, all_nitrogen, all_phosphorus, all_potassium FROM overall_data";
$result = $link->query($query);

if ($result) {
    // Fetch the data and store it in their respective arrays
    while ($row = $result->fetch_assoc()) {
        $all_air_temp[] = $row['all_air_temp'];
        $all_soil_temperature[] = $row['all_soil_temperature'];
        $all_nitrogen[] = $row['all_nitrogen'];
        $all_phosphorus[] = $row['all_phosphorus'];
        $all_potassium[] = $row['all_potassium'];
    }
    $result->free();
}

// $link->close();

// Function to get the latest average sensor data for all sensors
function getLatestSensorData() {
    global $link; // Use the database connection from config.php
    $sensorData = [];

    for ($sensorId = 1; $sensorId <= 3; $sensorId++) {
        $sql = "SELECT * FROM `rawsensor{$sensorId}` ORDER BY `reading_time` DESC LIMIT 1";
        $result = mysqli_query($link, $sql);

        if ($result) {
            $sensorData[$sensorId] = mysqli_fetch_assoc($result);
        } else {
            $sensorData[$sensorId] = null; // Handle the case where there is no data
        }
    }

    return $sensorData;
}

// Get the data for all sensors
$allSensorData = getLatestSensorData();
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
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
    
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Analytics</h2>
    <div class="container">
        <div class="row pick-nutrient">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="btnradio" id="nitrogen" autocomplete="off" checked>                    <label class="btn btn-outline-primary" for="btnradio1">Nitrogen</label>

                <input type="radio" class="btn-check" name="btnradio" id="phosphorus" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Phosphorus</label>

                <input type="radio" class="btn-check" name="btnradio" id="potassium" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio3">Potassium</label>
                
                <input type="radio" class="btn-check" name="btnradio" id="temperature" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio1">Temperature</label>

                <input type="radio" class="btn-check" name="btnradio" id="moisture" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Moisture</label>

                </div>
            </div>
        <div class="row sensor-specs">
            <div class="col value-time">
                <div class="row title">
                    <h4>Nitrogen</h4>
                </div>
                <div class="row sensor">
                    <h6>Sensor 1</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Timestamp</th>
                                <th class="valuee" scope="col">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allSensorData as $sensorId => $data): ?>
                            <tr>
                                <td><?php echo $data ? htmlspecialchars($data['reading_time']) : 'N/A';?></td>
                                <td class="values"><?php echo $data ? htmlspecialchars($data['nitrogen']) : 'N/A';?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h6>Sensor 2</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Timestamp</th>
                                <th class="valuee" scope="col">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allSensorData as $sensorId => $data): ?>
                            <tr>
                                <td><?php echo $data ? htmlspecialchars($data['reading_time']) : 'N/A';?></td>
                                <td class="values"><?php echo $data ? htmlspecialchars($data['nitrogen']) : 'N/A';?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h6>Sensor 3</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Timestamp</th>
                                <th class="valuee" scope="col">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allSensorData as $sensorId => $data): ?>
                            <tr>
                                <td><?php echo $data ? htmlspecialchars($data['reading_time']) : 'N/A';?></td>
                                <td class="values"><?php echo $data ? htmlspecialchars($data['nitrogen']) : 'N/A';?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="col trends-insights">
                <div class="row trends">
                    <h4>Nitrogen Trend Chart</h4>
                    <div class="row">
                        <div id="nitrogenChart" style="height: 400px;" class="chart-container"></div> 
                    </div>
                </div>
                <div class="row insights">
                    <h4>Insights</h4>
                    <div class="row">
                        
                    </div>
                </div>
            </div>


        </div>
            
        
        <div class="chart-container">
            <div class="row">
                <div class="col-lg-4">
                    <div id="airTempChart" style="height: 400px;" class="chart-container"></div> 
                </div>

                <div class="col-lg-4">
                    <div id="soilTempChart" style="height: 400px;" class="chart-container"></div> 
                </div>
                <div class="col-lg-4">
                    <div id="nitrogenChart" style="height: 400px;" class="chart-container"></div> 
                </div>
                <div class="col-lg-4">
                    <div id="phosphorusChart" style="height: 400px;" class="chart-container"></div> 
                </div>
                <div class="col-lg-4">
                    <div id="potassiumChart" style="height: 400px;" class="chart-container"></div> 
                </div>
                
            </div>
        </div>
    </div>
</div>
    <script>
    // Pass PHP array to JavaScript
    var airTempData = <?php echo json_encode($all_air_temp); ?>;
    
    Highcharts.chart('airTempChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Air Temperature Over Time'
        },
        xAxis: {
            // Optionally, add categories or labels if necessary, e.g., timestamps
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        series: [{
            name: 'Air Temperature',
            data: airTempData.map(Number) // Ensure data is in Number format
        }]
    });


    var soilData = <?php echo json_encode($all_soil_temperature); ?>;
    
    Highcharts.chart('soilTempChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: ' Soil Temperature Over Time'
        },
        xAxis: {
            // Optionally, add categories or labels if necessary, e.g., timestamps
        },
        yAxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        series: [{
            name: 'Soil Temperature',
            data: soilData.map(Number) // Ensure data is in Number format
        }]
    });

    var nitrogenData = <?php echo json_encode($all_nitrogen); ?>;
    
    Highcharts.chart('nitrogenChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: ' Nitrogen Over Time'
        },
        xAxis: {
            // Optionally, add categories or labels if necessary, e.g., timestamps
        },
        yAxis: {
            title: {
                text: 'Values'
            }
        },
        series: [{
            name: 'Time',
            data: nitrogenData.map(Number) // Ensure data is in Number format
        }]
    });

    var phosphorusData = <?php echo json_encode($all_phosphorus); ?>;
    
    Highcharts.chart('phosphorusChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: ' Phosphorus Over Time'
        },
        xAxis: {
            // Optionally, add categories or labels if necessary, e.g., timestamps
        },
        yAxis: {
            title: {
                text: 'P:'
            }
        },
        series: [{
            name: 'Phosphorus',
            data: phosphorusData.map(Number) // Ensure data is in Number format
        }]
    });

    
    var potassiumData = <?php echo json_encode($all_potassium); ?>;
    
    Highcharts.chart('potassiumChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: ' Potassium Over Time'
        },
        xAxis: {
            // Optionally, add categories or labels if necessary, e.g., timestamps
        },
        yAxis: {
            title: {
                text: 'K:'
            }
        },
        series: [{
            name: 'Potassium',
            data: potassiumData.map(Number) // Ensure data is in Number format
        }]
    });
    </script>
</div>

</body>
</html>
<style>
    @import url(https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic);
    :root {
        --primary-color: #f90a39;
        --text-color: #1d1d1d;
        --bg-color: #f1f1fb;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
            
    }
    body {
      background-color: #EDEDEC;
    }

    .main-content {
    margin-left: 250px;
    padding: 1em;
    }
    .pick-nutrient{
      margin-bottom: 20px;
    }

    .chart-container, .value-time  {
        padding: 10px;
        background-color: #ffffff;
        margin-bottom: 10px;
        border-radius: 8px;

    }
    .sensor-specs{
        padding: 10px;
        border-radius: 8px;
    }
    .trends, .insights{
        padding: 10px;
        margin-left: 10px;
        background-color: #ffffff;
        margin-bottom: 10px;
        border-radius: 8px;
    }

/* Highcharts specific styling */
.highcharts-figure, .highcharts-data-table table {
    min-width: 320px;
    max-width: 660px;
    margin: 1em auto;
}
h6{
    color: red;
}
    .values, .valuee{
        color:green;
    }
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }
    .sidebar {
        width: auto;
    }
    .chart-container {
        margin-bottom: 0;
    }
    .highcharts-figure, .highcharts-data-table table {
        min-width: 100%;
        max-width: 100%;
    }
}
</style>