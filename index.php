<?php

require_once 'config.php';

// Define the column names to be displayed
$displayedColumns = array(
    'Nitrogen' => 'nitrogen',
    'Potassium' => 'potassium',
    'Phosphorus' => 'phosphorus',
    'Soil Temperature' => 'soil_temp',
    'Air Temperature' => 'air_temp',
    'Soil Moisture' => 'soil_moisture'
);

// Array to hold the latest 3 values for each sensor
$sensorsData = array();

// Fetch the latest 3 values for each sensor column
foreach ($displayedColumns as $displayName => $columnName) {
    $sql = "SELECT reading_time, $columnName 
            FROM rawsensor1 
            ORDER BY reading_time DESC 
            LIMIT 3";
    
    $result = $link->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sensorsData[$displayName][] = array(
                "Timestamp" => $row["reading_time"],
                "Value" => $row[$columnName]
            );
        }
    }
}

// Close connection
$link->close();

// // Print the fetched data for debugging
// echo "<pre>";
// print_r($sensorsData);
// echo "</pre>";
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
    
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Dashboard</h2>
    <div class="container">
        <div class="row pick-sensor">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="sensor" id="sensor1" autocomplete="off" checked value="sensor1">
                    <label class="btn btn-outline-primary active" for="sensor1" onclick="setActive(this)">Bed 1</label>

                    <input type="radio" class="btn-check" name="sensor" id="sensor2" autocomplete="off" value="sensor2">
                    <label class="btn btn-outline-primary" for="sensor2" onclick="setActive(this)">Bed 2</label>

                    <input type="radio" class="btn-check" name="sensor" id="sensor3" autocomplete="off" value="sensor3">
                    <label class="btn btn-outline-primary" for="sensor3" onclick="setActive(this)">Bed 3</label>
            </div>
        </div>
        <div class="row  table-responsive mb-2 mt-2">
            
                <table id="sensorDataTable" class=" mt-3">
                    <tr>
                        <th>Timestamps</th>
                        <th>Nitrogen</th>
                        <th>Potassium</th>
                        <th>Phosphorus</th>
                        <th>Soil Temp</th>
                        <th>Air Temp</th>
                        <th>Soil Moisture</th>
                    </tr>
                </table>
            
        </div>
                
        <!-- <div class="row">
            <div class="col">
                <div class="row heatmap-header">
                    <div class="col-auto me-auto heatmap-title">Heatmap Calendar</div>
                    <div class="col-auto pick-crop">
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="btnradio" id="heatrice" autocomplete="off" checked>
                            <label class="btn btn-outline-primary" for="btnradio1">Rice</label>

                            <input type="radio" class="btn-check" name="btnradio" id="heatcorn" autocomplete="off">
                            <label class="btn btn-outline-primary" for="btnradio2">Corn</label>
                        </div>
                    </div>
                </div>
                <div class="row heatmap-body">
                    
                </div>
            </div>
        </div> -->
        
        <div class="row recommendations">
                <div class="row rec-header">
                    <span>Recommendations</span>
                </div>
                <div class="row">
                    <div class="recommendation-box" style="background-color: #598A6F;"></div>
                </div>
                <div class="row">
                    <div class="recommendation-box" style="background-color: #93F38B;"></div>
                </div>
                <div class="row">
                    <div class="recommendation-box" style="background-color: #F9EF97;"></div>
                </div>
        </div>
    </div>
</div>


<script>

        // Function to create dynamic table
        function createDynamicTable(sensor, sensorData) {
            let tableHTML = `<table><div class="sensor-title">${sensor}</div>`;
            tableHTML += '<tr><th>Timestamp</th><th>Value</th></tr>';
            sensorData.forEach(data => {
                tableHTML += `<tr><td>${data.Timestamp}</td><td>${data.Value}</td></tr>`;
            });
            tableHTML += '</table>';
            return `<div class="sensor-table">${tableHTML}</div>`;
        }



</script>
<script>
        function setActive(label) {
            // Remove active class from all labels
            document.querySelectorAll('.btn-group label').forEach(function (element) {
                element.classList.remove('active');
            });

            // Add active class to clicked label
            label.classList.add('active');

            fetchSensorData();

        }

function fetchSensorData() {
    var selectedSensor = document.querySelector('input[name="sensor"]:checked').value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("sensorDataTable").innerHTML = this.responseText;
            
        }
    };
    xhttp.open("GET", "fetch_sensor_data.php?sensor=" + selectedSensor, true);
    xhttp.send();
    updateSensorDataTable(selectedSensor);

}

// Function to update the sensor data table
function updateSensorDataTable(selectedSensor) {
    const sensorData = <?php echo json_encode($sensorsData);?>[selectedSensor];
    const sensorDataTable = document.getElementById("sensorDataTable");
    sensorDataTable.innerHTML = "";
    sensorData.forEach(data => {
        const row = document.createElement("tr");
        const timestampCell = document.createElement("td");
        timestampCell.textContent = data.Timestamp;
        const valueCell = document.createElement("td");
        valueCell.textContent = data.Value;
        row.appendChild(timestampCell);
        row.appendChild(valueCell);
        sensorDataTable.appendChild(row);
    });
}

window.onload = function() {
    fetchSensorData();
};

    function updateSensorDataTable(selectedSensor) {
    const sensorData = <?php echo json_encode($sensorsData);?>[selectedSensor];
    const sensorDataTable = document.getElementById("sensorDataTable");
    sensorDataTable.innerHTML = "";
    sensorData.forEach(data => {
        const row = document.createElement("tr");
        const timestampCell = document.createElement("td");
        timestampCell.textContent = data.Timestamp;
        const valueCell = document.createElement("td");
        valueCell.textContent = data.Value;
        row.appendChild(timestampCell);
        row.appendChild(valueCell);
        sensorDataTable.appendChild(row);
    });
}
    </script>
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

    .pick-sensor{
        /* margin-bottom: 20px; */
    }
    .col, .col-sm-8, .col-sm-4 {
      background-color: #ffffff; 
      border-radius: 8px;
      margin: 10px;
    }

    .Welcome {
      border-radius: 8px;
      padding: 15px;
    }
    .main-content {
    margin-left: 250px;
    padding: 1em;
    }
    
    .recommendation-box {
        width: 50px;
        height: 30px;
        margin: 5px; 
    }

    /* table {
        padding: 20px;
            border-collapse: collapse;
            width: 100%;
            background-color: #ffffff; 
            border-radius: 8px;
        } */
        th, td {
            text-align: center;
            padding: 8px;
        }
        th{
            background-color: forestgreen;
            color: white;
        }
        .sensor-table {
            width: 250px;
            padding: 10px;
        }
        .sensor-title{
            color: black;
            font-weight: bold;
            text-align: start;
            margin-bottom: 10px;
        }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
        .sidebar {
          width: auto;
        }
        
    }
    @media (min-width: 768px) {
        .welcome-column {
            display: none;
        }
    }
    /* Additional styles for buttons */
.btn-outline-primary.active,
.btn-outline-primary:focus{
    background-color: #111 !important;
    border-color: forestgreen !important;
    color: #0fc041 !important;
}
.btn-outline-primary:hover{
    background-color: gold;
}
.btn-group label {
    border-color: darkgreen;
    color: darkgreen;
    background-color: white;
}

</style>
<style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 5px;
            border-radius: 10px;
            overflow: hidden;
         }
        th {
            text-align: left;
            background-color: forestgreen;
            padding: 8px 20px; /* Add padding for spacing */
            color: white; /* Change text color to white */
        }
        td {
            padding: 8px 20px; /* Add padding for spacing */
            text-align: center;
            background-color: #f9f9f9; /* Set background color for all cells */
        }
        td:nth-child(odd) {
            background-color: #f9f9f9; /* Alternate background color for odd columns */
        }
        .btn-check {
            display: none;
        }
    </style>