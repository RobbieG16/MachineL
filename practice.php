<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            border-collapse: collapse;
            width: 80%; /* Increase table width */
            margin: 20px auto;
            border-radius: 10px; /* Add border radius for the whole table */
            overflow: hidden; /* Ensure border radius is applied correctly */
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
</head>
<body>
    <div class="container">
        <div class="row pick-sensor">
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="sensor" id="sensor1" autocomplete="off" checked value="sensor1">
                <label class="btn btn-outline-primary active" for="sensor1" onclick="setActive(this)">Sensor 1</label>

                <input type="radio" class="btn-check" name="sensor" id="sensor2" autocomplete="off" value="sensor2">
                <label class="btn btn-outline-primary" for="sensor2" onclick="setActive(this)">Sensor 2</label>

                <input type="radio" class="btn-check" name="sensor" id="sensor3" autocomplete="off" value="sensor3">
                <label class="btn btn-outline-primary" for="sensor3" onclick="setActive(this)">Sensor 3</label>
            </div>
        
            <table id="sensorDataTable" class="mt-3">
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
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function setActive(label) {
            // Remove active class from all labels
            document.querySelectorAll('.btn-group label').forEach(function (element) {
                element.classList.remove('active');
            });

            // Add active class to clicked label
            label.classList.add('active');

            // Fetch data for the selected sensor
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
        }

        // Initial data load when page loads
        fetchSensorData();
    </script>
</body>
</html>
