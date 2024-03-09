<?php
# Initialize the session
session_start();

# If user is not logged in then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User login system</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
  <link rel="stylesheet" href="./css/calendar.css">

</head>

<body>
<?php include 'sidebar.php'; ?>

<!-- Your main content goes here -->
<div class="main-content">

    <div class="carl" style="margin: auto;">
        <?php include('progress.php'); ?>

        <div class="row justify-content-center">
            <div class="legend">
                <div class="legends">
                <h3>Legends</h3>
                </div>
                <div class="row">
                <div class="col-4 text-center">
                    <div class="legend-container">
                        <div class="legend-color" style="background-color: green; width:"></div>
                        <span class="legend-label">Best</span>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="legend-container">
                        <div class="legend-color" style="background-color: yellow; width:"></div>
                        <span class="legend-label">Mid</span>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="legend-container">
                        <div class="legend-color" style="background-color: red; width: "></div>
                        <span class="legend-label">Worst</span>
                    </div>
                </div>
                </div>
            </div>
        </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="calendar margin-top-custom">
                        <div class="header">
                            <div class="month"></div>
                            <button type="button" class="btn btn-primary" id="runMLPredictionBtn">Run Machine Learning Prediction</button>

                            <div class="btns">
                                <div class="btn today-btn">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="btn prev-btn">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                                <div class="btn next-btn">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                        <div class="weekdays">
                            <div class="day">Sun</div>
                            <div class="day">Mon</div>
                            <div class="day">Tue</div>
                            <div class="day">Wed</div>
                            <div class="day">Thu</div>
                            <div class="day">Fri</div>
                            <div class="day">Sat</div>
                        </div>
                        <div class="days">
                        </div>
                    </div>
                    <div class="calendar2 udarbe">
                        <div class="header2">
                            <div class="month2"></div>
                            <button type="button" class="btn btn-primary" id="runHybridPredictionBtn">Run Hybrid Prediction</button>
                            <div class="btns2">
                                <div class="btn today-btn2">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="btn prev-btn2">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                                <div class="btn next-btn2">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                        <div class="weekdays2">
                            <div class="day2">Sun</div>
                            <div class="day2">Mon</div>
                            <div class="day2">Tue</div>
                            <div class="day2">Wed</div>
                            <div class="day2">Thu</div>
                            <div class="day2">Fri</div>
                            <div class="day2">Sat</div>
                        </div>
                        <div class="days2">

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="calendar2 margin-top-custom">
                        <div class="thresholds-item">
                            <h3>Corn Thresholds</h3>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Air Temp: </div>
                            <span class="threshold-value">21 - 32 Celsius</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Soil Temp: </div>
                            <span class="threshold-value">at least 15 Celsius</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Nitrogen: </div>
                            <span class="threshold-value">120</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Phosphorus: </div>
                            <span class="threshold-value">60</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Potassium: </div>
                            <span class="threshold-value">90</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Solar Radiation: </div>
                            <span class="threshold-value">15 - 25 mJ/m2/day</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Raifnall: </div>
                            <span class="threshold-value">25 - 50 mm</span>
                        </div>


                        <h3>Rice Thresholds</h3>
                        <div class="threshold-item">
                            <div class="threshold-label">Air Temp: </div>
                            <span class="threshold-value">21 - 32 Celsius</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Soil Temp: </div>
                            <span class="threshold-value">at least 15 Celsius</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Nitrogen: </div>
                            <span class="threshold-value">120</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Phosphorus: </div>
                            <span class="threshold-value">60</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Potassium: </div>
                            <span class="threshold-value">90</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Solar Radiation: </div>
                            <span class="threshold-value">15 - 25 mJ/m2/day</span>
                        </div>
                        <div class="threshold-item">
                            <div class="threshold-label">Raifnall: </div>
                            <span class="threshold-value">25 - 50 mm</span>
                        </div>
                    </div>
                </div>
                
            </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>
    </div>
 

</div>
<script>
  $(document).ready(function() {
    // Define a function to handle the click event of the button
    $("#runMLPredictionBtn").click(function() {
      // Use AJAX to send a request to ken.php
      $.ajax({
        type: "GET", // You can change the request type if needed
        url: "ken.php", // The URL of your ken.php script
        success: function(response) {
          // Handle the success response if needed
          console.log("Machine Learning Prediction executed successfully");
        },
        error: function(error) {
          // Handle the error response if needed
          console.error("Error executing Machine Learning Prediction", error);
        }
      });
    });
  });
</script>
<script>
  $(document).ready(function() {
    // Define a function to handle the click event of the button
    $("#runHybridPredictionBtn").click(function() {
      // Use AJAX to send a request to glen.php
      $.ajax({
        type: "GET", // You can change the request type if needed
        url: "glen.php", // The URL of your glen.php script
        success: function(response) {
          // Handle the success response if needed
          console.log("Hybrid Prediction executed successfully");
        },
        error: function(error) {
          // Handle the error response if needed
          console.error("Error executing Hybrid Prediction", error);
        }
      });
    });
  });
</script>

<script src="./jquery-3.5.1.slim.min.js"></script>

<script src="./js/troubleshoot/script.js"></script>
<script src="./js/troubleshoot/script2.js"></script>
<!-- Include jQuery -->

<!-- Include Bootstrap JS (Make sure this comes after jQuery) -->
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>

</html>
