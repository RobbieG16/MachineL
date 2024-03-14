<?php
include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $cropName = mysqli_real_escape_string($link, $_POST['cropName']);
    $nutrients = array(
        'nitrogen' => mysqli_real_escape_string($link, $_POST['nitrogen']),
        'phosphorus' => mysqli_real_escape_string($link, $_POST['phosphorus']),
        'potassium' => mysqli_real_escape_string($link, $_POST['potassium']),
        'air_temp' => mysqli_real_escape_string($link, $_POST['air_temp']),
        'soil_temp' => mysqli_real_escape_string($link, $_POST['soil_temp']),
        'soil_moisture' => mysqli_real_escape_string($link, $_POST['soil_moisture'])
    );

    $weatherParams = array(
        'rel_hum' => mysqli_real_escape_string($link, $_POST['rel_hum']),
        'max_temp' => mysqli_real_escape_string($link, $_POST['max_temp']),
        'min_temp' => mysqli_real_escape_string($link, $_POST['min_temp']),
        'sol_rad' => mysqli_real_escape_string($link, $_POST['sol_rad']),
        'rainfall' => mysqli_real_escape_string($link, $_POST['rainfall'])
    );

$sql = "INSERT INTO threshold (crop_name, nitrogen, phosphorus, potassium, air_temp, soil_temp, soil_moisture, rel_hum, max_temp, min_temp, sol_rad, rainfall)
            VALUES ('$cropName', '{$nutrients['nitrogen']}', '{$nutrients['phosphorus']}', '{$nutrients['potassium']}', '{$nutrients['air_temp']}', '{$nutrients['soil_temp']}', '{$nutrients['soil_moisture']}', '{$weatherParams['rel_hum']}', '{$weatherParams['max_temp']}', '{$weatherParams['min_temp']}', '{$weatherParams['sol_rad']}', '{$weatherParams['rainfall']}')
            ON DUPLICATE KEY UPDATE
            nitrogen = '{$nutrients['nitrogen']}',
            phosphorus = '{$nutrients['phosphorus']}',
            potassium = '{$nutrients['potassium']}',
            air_temp = '{$nutrients['air_temp']}',
            soil_temp = '{$nutrients['soil_temp']}',
            soil_moisture = '{$nutrients['soil_moisture']}',
            rel_hum = '{$weatherParams['rel_hum']}',
            max_temp = '{$weatherParams['max_temp']}',
            min_temp = '{$weatherParams['min_temp']}',
            sol_rad = '{$weatherParams['sol_rad']}',
            rainfall = '{$weatherParams['rainfall']}';";

    // Execute the query
    if (mysqli_query($link, $sql)) {
        // echo "Data inserted successfully.";
    } else {
        // echo "Error: " . mysqli_error($link);
    }
}
?>




<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User login system</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
</head>


<body>
<?php include 'sidebar.php'; ?>

<!-- Your main content goes here -->
<div class="main-content">

  <h2 class="text-center mb-4">Controller</h2>

  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <div class="row Time-head">
          <h4 class="text-center" >Custom Time Interval</h4>

        </div>
        <!-- Form -->
        <form action="controller.php" method="post" onsubmit="return confirmChange()">
          <label for="intervalSelect">Set Time (1 hour - 8 hours):</label>
          <select class="custom-select" id="intervalSelect" name="interval">
            <option value="3600000" <?php if (isset($currentInterval) && $currentInterval == 3600000) echo 'selected'; ?>>1 hour</option>
            <option value="7200000" <?php if (isset($currentInterval) && $currentInterval == 7200000) echo 'selected'; ?>>2 hours</option>
            <option value="10800000" <?php if (isset($currentInterval) && $currentInterval == 10800000) echo 'selected'; ?>>3 hours</option>
            <option value="14400000" <?php if (isset($currentInterval) && $currentInterval == 14400000) echo 'selected'; ?>>4 hours</option>
            <option value="18000000" <?php if (isset($currentInterval) && $currentInterval == 18000000) echo 'selected'; ?>>5 hours</option>
            <option value="21600000" <?php if (isset($currentInterval) && $currentInterval == 21600000) echo 'selected'; ?>>6 hours</option>
            <option value="25200000" <?php if (isset($currentInterval) && $currentInterval == 25200000) echo 'selected'; ?>>7 hours</option>
            <option value="28800000" <?php if (isset($currentInterval) && $currentInterval == 28800000) echo 'selected'; ?>>8 hours</option>
          </select>
          <input type="submit" class="btn btn-primary mt-2 mb-4" value="Set Interval">
        </form>
        
        <!-- Text field for adjustable times -->
        <form action="controller.php" method="post" onsubmit="return setAdjustableTime()">
          <div class="input-group mb-3">
              <input type="number" class="form-control" id="adjustableTime" name="adjustableTime" placeholder="Adjustable Time (ms)" aria-label="Adjustable Time" aria-describedby="adjustableTimeAddon">
              <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="submit">Set</button>
              </div>
          </div>
        </form>
      </div>
      
    </div>



    <div class="row">
      <div class="col">
        <form action="deploy.php" method="post" onsubmit="return applyCropParameters()"> 
          <div class="row p-head text-center">
            <h4>Custom Crop Parameters</h4>
            <h6 class="sub-head" >Input Optimal Weather and NPK Values</h6>
            <div class="input-group mb-3 align-items-center">
              <h5 class="mr-3" >Crop name:</h5>
              <input type="text" class="form-control" id="cropName" name="cropName" placeholder="Rice / Corn">
            </div>
          </div>
          <div class="row p-body">
              <div class="row edit">
                <div class="col nutrients">
            <?php
            $nutrients = array(
                "Nitrogen" => "nitrogen",
                "Phosphorus" => "phosphorus",
                "Potassium" => "potassium",
                "Air Temperature" => "air_temp",
                "Soil Temperature" => "soil_temp",
                "Soil Moisture" => "soil_moisture"
            );

            foreach ($nutrients as $label => $name) {
            ?>
                <div class="row">
                    <div class="input-group">
                        <input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" placeholder="kg/Ha">
                        <span class="input-group-text">
                          <h5 class="mr-3"><?php echo $label; ?></h5>
                        </span>                    
                      </div>
                </div>
            <?php
            }
            ?>
                </div>
                <div class="col weather">
          <?php
          $weatherParams = array(
              "Relative Humidity" => "rel_hum",
              "Maximum Temperature" => "max_temp",
              "Minimum Temperature" => "min_temp",
              "Solar Radiation" => "sol_rad",
              "Rainfall" => "rainfall"
          );

          foreach ($weatherParams as $label => $name) {
          ?>
              <div class="row">
                  <div class="input-group">
                      <input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" placeholder="°C">
                      <span class="input-group-text">
                        <h5 class="mr-3"><?php echo $label; ?></h5>
                      </span> 
                  </div>
              </div>
          <?php
          }
          ?>
                </div>
              </div>
              <div class="row confirm">
                <button type="submit" class="btn btn-primary mx-auto">Apply</button>
              </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>




</div> 

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  // Function to confirm the interval change
  function confirmChange() {
    return confirm("Are you sure you want to set the interval?");
  }

  // Function to confirm setting the adjustable time
  function setAdjustableTime() {
    var adjustableTimeField = document.getElementById("adjustableTime");
    var adjustableTime = parseInt(adjustableTimeField.value); // Get the value from the text field as milliseconds
    if (adjustableTime >= 1 && adjustableTime <= 28800000) { // Ensure the adjustable time is within the range (1ms to 8 hours)
      if (confirm("Are you sure you want to set the interval?")) {
        alert("Adjustable time is set to " + adjustableTime + "ms!");
        return true;
      }
    } else {
      alert("Please enter a time between 1ms and 8 hours in milliseconds.");
    }
    return false; // Prevent form submission
  }

  // Function to increase time
  function increaseTime() {
    var adjustableTimeField = document.getElementById("adjustableTime");
    adjustableTimeField.stepUp();
  }

  // Function to decrease time
  function decreaseTime() {
    var adjustableTimeField = document.getElementById("adjustableTime");
    adjustableTimeField.stepDown();
  }
</script>


<script>
  function applyCropParameters() {
    // Retrieve form data
    const cropName = document.getElementById('cropName').value;
    const nitrogen = document.getElementById('nitrogen').value;
    const phosphorus = document.getElementById('phosphorus').value;
    const potassium = document.getElementById('potassium').value;
    const airTemp = document.getElementById('air_temp').value;
    const soilTemp = document.getElementById('soil_temp').value;
    const soilMoisture = document.getElementById('soil_moisture').value;
    const relHumidity = document.getElementById('rel_hum').value;
    const maxTemp = document.getElementById('max_temp').value;
    const minTemp = document.getElementById('min_temp').value;
    const solarRadiation = document.getElementById('sol_rad').value;
    const rainfall = document.getElementById('rainfall').value;

    // Prepare the alert message
    const alertMessage = `
      Database updated.
      Crop name: ${cropName}
      Nitrogen: ${nitrogen}
      Phosphorus: ${phosphorus}
      Potassium: ${potassium}
      Air Temperature: ${airTemp}
      Soil Temperature: ${soilTemp}
      Soil Moisture: ${soilMoisture}
      Relative Humidity: ${relHumidity}
      Maximum Temperature: ${maxTemp}
      Minimum Temperature: ${minTemp}
      Solar Radiation: ${solarRadiation}
      Rainfall: ${rainfall}
    `;

    // Display the alert message
    alert(alertMessage);

   // Send the form data to the server
   const xhr = new XMLHttpRequest();
    xhr.open('POST', 'deploy.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
  if (xhr.status === 200) {
    // Parse the JSON response from the server
    const response = JSON.parse(xhr.responseText);

    // Check if the update was successful
    if (response.success) {
      alert("Update successful: " + response.message);
    } else {
      alert("Update failed: " + response.message);
    }
  } else {
    alert('Error: ' + xhr.statusText);
  }
};
    xhr.send(`cropName=${cropName}&nitrogen=${nitrogen}&phosphorus=${phosphorus}&potassium=${potassium}&air_temp=${airTemp}&soil_temp=${soilTemp}&soil_moisture=${soilMoisture}&rel_hum=${relHumidity}&max_temp=${maxTemp}&min_temp=${minTemp}&sol_rad=${solarRadiation}&rainfall=${rainfall}`);

    // Assuming applyCropParameters should return true/false based on the success of the operation
    return true; // Return true to allow form submission, or return false to prevent it
  }
</script>

</body>

</html>
<style>
    body {
      background-color: #EDEDEC; /* Light background */
    }
    .main-content {
    margin-left: 250px; /* Same as the width of your sidebar */
    padding: 1em;
    }
    .col {
      padding: 20px;
      max-width: 800px; /* responsive width */
      margin: auto;
      background: white; /* Clear distinction from the background */
      border-radius: 8px; /* Softened edges */
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    }
    .row {
      margin-bottom: 20px;
      justify-content: center;
    }
    .custom-select {
      cursor: pointer; /* Indicates interactivity */
    }
    .custom-select:focus {
      outline: none; /* Removes default focus outline */
      box-shadow: none; /* Removes default focus shadow */
    }
    .output {
      color: #FF0000; 
      font-weight: bold; 
    }
    .edit .col {
        box-shadow: none;
    }
    .edit .form-control{
      width: 100px;
    }
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0; 
        }
        .sidebar {
          width: auto;
        }
    }
    /* @media screen and (min-width: 768px) {
        .welcome-column {
            display: none;
        }
    } */
</style>
