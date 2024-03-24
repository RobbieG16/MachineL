<?php
include 'config.php';
print_r($_POST);

// Check if form data is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the necessary keys exist in the $_POST array
  if (isset($_POST['cropName']) && isset($_POST['nitrogen']) && isset($_POST['phosphorus']) && isset($_POST['potassium']) &&
      isset($_POST['air_temp']) && isset($_POST['soil_temp']) && isset($_POST['soil_moisture']) &&
      isset($_POST['rel_hum']) &&
      isset($_POST['sol_rad']) && isset($_POST['rainfall']) && isset($_POST['months'])) {

      // Retrieve form data
      $cropName = $_POST['cropName'];
      $months = $_POST['months'];

      // Check if the selected months are already associated with another crop
      $existingMonths = array();
      $existingCrop = '';
      $sqlCheck = "SELECT crop_name, months FROM threshold";
      $resultCheck = mysqli_query($link, $sqlCheck);
      if ($resultCheck) {
          while ($row = mysqli_fetch_assoc($resultCheck)) {
              $existingMonths[$row['crop_name']] = explode(',', $row['months']);
          }
          foreach ($existingMonths as $existingCropName => $existingCropMonths) {
              if ($existingCropName != $cropName) {
                  $duplicateMonths = array_intersect($months, $existingCropMonths);
                  if (!empty($duplicateMonths)) {
                      $existingCrop = $existingCropName;
                      break;
                  }
              }
          }
      }

      // If there are duplicate months, display an alert message and stop further processing
      if (!empty($existingCrop)) {
          echo "<script>alert('The selected month(s) are already associated with the crop: $existingCrop. Please remove the duplicate month(s) if you wish to continue.');</script>";
          exit; // Stop further processing
      }

      // Retrieve and parse the nutrient values
      $nutrients = [
          'nitrogen' => explode('-', $_POST['nitrogen']),
          'phosphorus' => explode('-', $_POST['phosphorus']),
          'potassium' => explode('-', $_POST['potassium']),
          'air_temp' => explode('-', $_POST['air_temp']),
          'soil_temp' => explode('-', $_POST['soil_temp']),
          'soil_moisture' => explode('-', $_POST['soil_moisture']),
      ];

      // Retrieve and parse the weather parameter values
      $weatherParams = [
          'rel_hum' => explode('-', $_POST['rel_hum']),
          'sol_rad' => explode('-', $_POST['sol_rad']),
          'rainfall' => explode('-', $_POST['rainfall']),
      ];

      // Serialize the selected month IDs
      $months = implode(',', $_POST['months']);

      // Prepare the SQL query
      $sql = "INSERT INTO threshold (crop_name, months,
                min_nitrogen, max_nitrogen,
                min_phosphorus, max_phosphorus,
                min_potassium, max_potassium,
                min_air_temp, max_air_temp,
                min_soil_temp, max_soil_temp,
                min_soil_moisture, max_soil_moisture,
                min_rel_hum, max_rel_hum,
                min_sol_rad, max_sol_rad,
                min_rainfall, max_rainfall)
              VALUES ('$cropName', '$months',
                      '{$nutrients['nitrogen'][0]}', '{$nutrients['nitrogen'][1]}',
                      '{$nutrients['phosphorus'][0]}', '{$nutrients['phosphorus'][1]}',
                      '{$nutrients['potassium'][0]}', '{$nutrients['potassium'][1]}',
                      '{$nutrients['air_temp'][0]}', '{$nutrients['air_temp'][1]}',
                      '{$nutrients['soil_temp'][0]}', '{$nutrients['soil_temp'][1]}',
                      '{$nutrients['soil_moisture'][0]}', '{$nutrients['soil_moisture'][1]}',
                      '{$weatherParams['rel_hum'][0]}', '{$weatherParams['rel_hum'][1]}',
                      '{$weatherParams['sol_rad'][0]}', '{$weatherParams['sol_rad'][1]}',
                      '{$weatherParams['rainfall'][0]}', '{$weatherParams['rainfall'][1]}')
              ON DUPLICATE KEY UPDATE
                      months = '$months',
                      min_nitrogen = '{$nutrients['nitrogen'][0]}',
                      max_nitrogen = '{$nutrients['nitrogen'][1]}',
                      min_phosphorus = '{$nutrients['phosphorus'][0]}',
                      max_phosphorus = '{$nutrients['phosphorus'][1]}',
                      min_potassium = '{$nutrients['potassium'][0]}',
                      max_potassium = '{$nutrients['potassium'][1]}',
                      min_air_temp = '{$nutrients['air_temp'][0]}',
                      max_air_temp = '{$nutrients['air_temp'][1]}',
                      min_soil_temp = '{$nutrients['soil_temp'][0]}',
                      max_soil_temp = '{$nutrients['soil_temp'][1]}',
                      min_soil_moisture = '{$nutrients['soil_moisture'][0]}',
                      max_soil_moisture = '{$nutrients['soil_moisture'][1]}',
                      min_rel_hum = '{$weatherParams['rel_hum'][0]}',
                      max_rel_hum = '{$weatherParams['rel_hum'][1]}',
                      min_sol_rad = '{$weatherParams['sol_rad'][0]}',
                      max_sol_rad = '{$weatherParams['sol_rad'][1]}',
                      min_rainfall = '{$weatherParams['rainfall'][0]}',
                      max_rainfall = '{$weatherParams['rainfall'][1]}'";

      // Execute the query
      if (mysqli_query($link, $sql)) {
          echo "Data inserted successfully.";
      } else {
          echo "Error: " . mysqli_error($link);
      }
  } else {
      // Handle the case where required form data is missing
      echo "Error: Required form data is missing.";
  }
} else {
  // Handle the case where the request method is not POST
  // echo "Error: This page only accepts POST requests.";
}
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
  <link rel="stylesheet" href="./css/settings.css">
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
              <option value="43200000" <?php if (isset($currentInterval) && $currentInterval == 43200000) echo 'selected'; ?>>12 hours</option>
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
               <h5 class="mr-3">Crop name:</h5>
                    <select class="form-control" id="cropName" name="cropName">
                        <option value="Rice">Rice</option>
                        <option value="Corn">Corn</option>
                    </select>
              </div>

              <div class="select-months">
                <?php
                $months = array(
                  "January" => 1, "February" => 2, "March" => 3, "April" => 4, "May" => 5, "June" => 6,
                  "July" => 7, "August" => 8, "September" => 9, "October" => 10, "November" => 11, "December" => 12
                );

                foreach ($months as $month => $monthId) {
                ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="month_<?php echo $monthId; ?>" name="months[]" value="<?php echo $monthId; ?>">
                        <label class="form-check-label" for="month_<?php echo $monthId; ?>"><?php echo $month; ?></label>
                    </div>
                <?php
                }
                ?>
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
                      <input type="text" class="form-control" id="<?php echo $name; ?>" name="<?php echo $name; ?>" placeholder="Min-Max">
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
    
    // Retrieve and parse the nutrient values
    const nutrients = {
      nitrogen: document.getElementById('nitrogen').value.split('-'),
      phosphorus: document.getElementById('phosphorus').value.split('-'),
      potassium: document.getElementById('potassium').value.split('-'),
      airTemp: document.getElementById('air_temp').value.split('-'),
      soilTemp: document.getElementById('soil_temp').value.split('-'),
      soilMoisture: document.getElementById('soil_moisture').value.split('-')
    };

    // Retrieve and parse the weather parameter values
    const weatherParams = {
      relHumidity: document.getElementById('rel_hum').value.split('-'),
      maxTemp: document.getElementById('max_temp').value.split('-'),
      minTemp: document.getElementById('min_temp').value.split('-'),
      solarRadiation: document.getElementById('sol_rad').value.split('-'),
      rainfall: document.getElementById('rainfall').value.split('-')
    };

    // Prepare the alert message
    const alertMessage = `
      Database updated.
      Crop name: ${cropName}
      Nitrogen: ${nutrients.nitrogen[0]} (min) - ${nutrients.nitrogen[1]} (max)
      Phosphorus: ${nutrients.phosphorus[0]} (min) - ${nutrients.phosphorus[1]} (max)
      Potassium: ${nutrients.potassium[0]} (min) - ${nutrients.potassium[1]} (max)
      Air Temperature: ${nutrients.airTemp[0]} (min) - ${nutrients.airTemp[1]} (max)
      Soil Temperature: ${nutrients.soilTemp[0]} (min) - ${nutrients.soilTemp[1]} (max)
      Soil Moisture: ${nutrients.soilMoisture[0]} (min) - ${nutrients.soilMoisture[1]} (max)
      Relative Humidity: ${weatherParams.relHumidity[0]} (min) - ${weatherParams.relHumidity[1]} (max)
      Maximum Temperature: ${weatherParams.maxTemp[0]} (min) - ${weatherParams.maxTemp[1]} (max)
      Minimum Temperature: ${weatherParams.minTemp[0]} (min) - ${weatherParams.minTemp[1]} (max)
      Solar Radiation: ${weatherParams.solarRadiation[0]} (min) - ${weatherParams.solarRadiation[1]} (max)
      Rainfall: ${weatherParams.rainfall[0]} (min) - ${weatherParams.rainfall[1]} (max)
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
    
    // Construct the data to be sent in the format expected by PHP
    const data = `cropName=${cropName}&` +
      `min_nitrogen=${nutrients.nitrogen[0]}&max_nitrogen=${nutrients.nitrogen[1]}&` +
      `min_phosphorus=${nutrients.phosphorus[0]}&max_phosphorus=${nutrients.phosphorus[1]}&` +
      `min_potassium=${nutrients.potassium[0]}&max_potassium=${nutrients.potassium[1]}&` +
      `min_air_temp=${nutrients.airTemp[0]}&max_air_temp=${nutrients.airTemp[1]}&` +
      `min_soil_temp=${nutrients.soilTemp[0]}&max_soil_temp=${nutrients.soilTemp[1]}&` +
      `min_soil_moisture=${nutrients.soilMoisture[0]}&max_soil_moisture=${nutrients.soilMoisture[1]}&` +
      `min_rel_hum=${weatherParams.relHumidity[0]}&max_rel_hum=${weatherParams.relHumidity[1]}&` +
      `min_max_temp=${weatherParams.maxTemp[0]}&max_max_temp=${weatherParams.maxTemp[1]}&` +
      `min_min_temp=${weatherParams.minTemp[0]}&max_min_temp=${weatherParams.minTemp[1]}&` +
      `min_sol_rad=${weatherParams.solarRadiation[0]}&max_sol_rad=${weatherParams.solarRadiation[1]}&` +
      `min_rainfall=${weatherParams.rainfall[0]}&max_rainfall=${weatherParams.rainfall[1]}`;
      xhr.send(data);

  // Assuming applyCropParameters should return true/false based on the success of the operation
  return true; // Return true to allow form submission, or return false to prevent it
  }
</script>


</body>

</html>

