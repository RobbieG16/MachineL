<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cropName'])) {
        $cropName = $_POST['cropName'];

        $sql = "INSERT INTO threshold (crop_name";

        $params = []; 

        $nutrients = [
            'nitrogen' => 'N',
            'phosphorus' => 'P',
            'potassium' => 'K',
            'air_temp' => 'AirTemperature',
            'soil_temp' => 'SoilTemperature',
            'soil_moisture' => 'SoilMoisture',
        ];

        foreach ($nutrients as $key => $label) {
            if (isset($_POST[$key]) && $_POST[$key] !== '') {
                $sql .= ", min_$key, max_$key";
                $params["min_$key"] = explode('-', $_POST[$key])[0];
                $params["max_$key"] = explode('-', $_POST[$key])[1];
            }
        }

        $weatherParams = [
            'rel_hum' => 'RelativeHumidity',
            'sol_rad' => 'SolarRadiation',
            'rainfall' => 'Rainfall',
        ];

        foreach ($weatherParams as $key => $label) {
            if (isset($_POST[$key]) && $_POST[$key] !== '') {
                $sql .= ", min_$key, max_$key";
                $params["min_$key"] = explode('-', $_POST[$key])[0];
                $params["max_$key"] = explode('-', $_POST[$key])[1];
            }
        }

        $sql .= ") VALUES ('$cropName'";

        foreach ($params as $param) {
            $sql .= ", '$param'";
        }

        $sql .= ") ON DUPLICATE KEY UPDATE ";

        foreach ($params as $param => $value) {
            $sql .= "$param = CASE WHEN VALUES($param) IS NOT NULL THEN VALUES($param) ELSE $param END, ";
        }

        $sql = rtrim($sql, ", ");

        if (mysqli_query($link, $sql)) {
            echo json_encode(array("success" => true, "message" => "Data inserted successfully."));
        } else {
            echo json_encode(array("success" => false, "message" => "Error: " . mysqli_error($link)));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Error: Required form data is missing."));
    }
} else {
    // echo json_encode(array("success" => false, "message" => "Error: This page only accepts POST requests."));
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
  <!-- <link rel="stylesheet" href="./css/settings.css"> -->
</head>

<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2 class="text-center mb-4">Settings</h2>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="row">
                    <h5>Custom Time Interval</h5>
                </div>
                <div class="row">
                    <div class="col-md">
                        <!-- Form -->
                        <form action="controller.php" method="post" onsubmit="return confirmChange()">
                            <label for="intervalSelect">Set Time (1 hour - 12 hours):</label>
                            <select class="custom-select col-md-6" id="intervalSelect" name="interval">
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
                            <input type="submit" class="btn btn-primary custom-btn" value="Set Interval">
                        </form>
                    </div>
                    <div class="col-md-4">
                        <form action="controller.php" method="post" onsubmit="return setAdjustableTime()">
                            <div class="input-group">
                                <input type="number" class="form-control col-md" id="adjustableTime" name="adjustableTime" placeholder="Adjustable Time (ms)" aria-label="Adjustable Time" aria-describedby="adjustableTimeAddon">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">Set</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div id="custom-threshold-form">
                    <?php include 'custom_threshold.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.2/dist/umd/popper.min.js"></script> -->

<script>
  function confirmChange() {
    return confirm("Are you sure you want to set the interval?");
  }

  function setAdjustableTime() {
    var adjustableTimeField = document.getElementById("adjustableTime");
    var adjustableTime = parseInt(adjustableTimeField.value);
    if (adjustableTime >= 1 && adjustableTime <= 28800000) { 
      if (confirm("Are you sure you want to set the interval?")) {
        alert("Adjustable time is set to " + adjustableTime + "ms!");
        return true;
      }
    } else {
      alert("Please enter a time between 1ms and 8 hours in milliseconds.");
    }
    return false;
  }

  function increaseTime() {
    var customIntervalSelect = document.getElementById("intervalSelect");
    var currentInterval = parseInt(customIntervalSelect.value);
    var maxInterval = 43200000;
    if (currentInterval < maxInterval) {
      customIntervalSelect.value = currentInterval + 3600000;
    }
  }

  function decreaseTime() {
    var customIntervalSelect = document.getElementById("intervalSelect");
    var currentInterval = parseInt(customIntervalSelect.value);
    var minInterval = 3600000;
    if (currentInterval > minInterval) {
      customIntervalSelect.value = currentInterval - 3600000;
    }
  }

  $(document).ready(function() {
    $('#custom-threshold-form form').submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: 'custom_threshold.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          var alertType = response.success ? 'alert-success' : 'alert-danger';
          var alertMessage = '<div class="alert ' + alertType + '">' + response.message + '</div>';
          $('#custom-threshold-form').prepend(alertMessage);
        }
      });
    });
  });
</script>
</body>
</html>

<style>
.custom-btn {
    background-color: forestgreen;
    border-color: forestgreen;
}
</style>