<?php
include 'config.php';
// Fetch threshold values from the database for Rice and Corn crops
function fetchThresholdsFromDatabase($link) {
    $thresholds = array();

    // Fetch thresholds from the database table for Rice and Corn crops
    $query = "SELECT * FROM threshold WHERE crop_name IN ('Rice', 'Corn')";
    $result = mysqli_query($link, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $thresholds[] = array(
                "crop_name" => $row['crop_name'],
                "min_nitrogen" => $row['min_nitrogen'],
                "max_nitrogen" => $row['max_nitrogen'],
                "min_phosphorus" => $row['min_phosphorus'],
                "max_phosphorus" => $row['max_phosphorus'],
                "min_potassium" => $row['min_potassium'],
                "max_potassium" => $row['max_potassium'],
                "min_air_temp" => $row['min_air_temp'],
                "max_air_temp" => $row['max_air_temp'],
                "min_soil_temp" => $row['min_soil_temp'],
                "max_soil_temp" => $row['max_soil_temp'],
                "min_soil_moisture" => $row['min_soil_moisture'],
                "max_soil_moisture" => $row['max_soil_moisture'],
                "min_rel_hum" => $row['min_rel_hum'],
                "max_rel_hum" => $row['max_rel_hum'],
                "min_sol_rad" => $row['min_sol_rad'],
                "max_sol_rad" => $row['max_sol_rad'],
                "min_rainfall" => $row['min_rainfall'],
                "max_rainfall" => $row['max_rainfall']
            );
        }
        mysqli_free_result($result);
    } else {
        echo "Error: " . mysqli_error($link);
    }

    return $thresholds;
}

// Fetch threshold values from the database for Rice and Corn crops
$thresholds = fetchThresholdsFromDatabase($link);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <!-- <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon"> -->
  <link rel="stylesheet" href="./css/calendar.css">
  <link rel="stylesheet" href="./css/button.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-*********" crossorigin="anonymous" />

</head>

<body>
<?php include 'sidebar.php'; ?>

<!-- Your main content goes here -->
<div class="main-content">
    <h2 class="mb-4">Calendar</h2>
    <div class="row">
        <div class="col">
            <div class="presto">
                <h5>Legends</h5>
                    <div class="row">
                        <div class="col">
                            <span>Maximum Yield Prediction</span>
                            <div class="row d-flex justify-content-center">
                                <div class="box " style="background-color: Green; max-width:50px;height:50px"> &nbsp </div>
                            </div>   
                        </div>
                        <div class="col">
                            <span>Average Yield Prediction</span>
                            <div class="row d-flex justify-content-center">
                                <div class="box " style="background-color: yellow; max-width:50px;height:50px;"> &nbsp</div>
                            </div>
                        </div>
                        <div class="col">
                            Minimum Yield Prediction
                            <div class="row d-flex justify-content-center">
                                <div class="box " style="background-color: Red; max-width:50px;height:50px;"> &nbsp </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>









    <div class="carl" style="margin: auto;">
        <div class="row justify-content-center">
            <div class="col">
                <h2>MACHINE LEARNING PREDICTION</h2>
                <div class="agbayani">
                    <div class="row">
                            <div class="header d-flex justify-content-between">
                                <div class="month"></div>

                                <div class="icon">
                                    <?php
                                    $currentMonth = date('n');
                                    $iconSrc = '';
                                    if (($currentMonth >= 11 && $currentMonth <= 12) || ($currentMonth >= 1 && $currentMonth <= 3)) {
                                        $iconSrc = './img/corn.png';
                                    } elseif (($currentMonth >= 4 && $currentMonth <= 10) || ($currentMonth == 13)) {
                                        $iconSrc = './img/rice.png';
                                    } else {
                                        $iconSrc = './img/offseason.png';
                                    }
                                    ?>
                                    <img src="<?= $iconSrc ?>" alt="Crop Icon" style="width: 50px; height: 40px;">
                                </div>


                                <div class="eugine">
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
                            </div>
                    </div>
                    <div class="row weekdays">
                            <div class="day">Sun</div>
                            <div class="day">Mon</div>
                            <div class="day">Tue</div>
                            <div class="day">Wed</div>
                            <div class="day">Thu</div>
                            <div class="day">Fri</div>                                <div class="day">Sat</div>
                    </div>
                    <div class="row days">
                        <!-- Add days using javascript -->
                    </div>
                </div>
                <div class="row justify-content-center">
                        <button class="button-62"id="runMLPredictionBtn" role="button  ">Run Machine Learning Prediction</button>
                </div>
            </div> 

            <div class="col">
                <h2>HYBRID ALGORITHM PREDICTION</h2>
                <div class="udarbe">
                    <div class="row">
                        <div class="header2 d-flex justify-content-between">
                            <div class="month2"></div>
                            <div class="icon">
                                <?php
                                $currentMonth = date('n');
                                $iconSrc = '';
                                if (($currentMonth >= 11 && $currentMonth <= 12) || ($currentMonth >= 1 && $currentMonth <= 3)) {
                                    $iconSrc = './img/corn.png';
                                } elseif (($currentMonth >= 4 && $currentMonth <= 10) || ($currentMonth == 13)) {
                                    $iconSrc = './img/rice.png';
                                } else {
                                    $iconSrc = './img/offseason.png';
                                }
                                ?>
                                <img src="<?= $iconSrc ?>" alt="Crop Icon" style="width: 50px; height: 40px;">
                                
                            </div>

                            <div class="taguro">
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
                        </div>
                    </div>
                    <div class="row weekdays2">
                                <div class="day2">Sun</div>
                                <div class="day2">Mon</div>
                                <div class="day2">Tue</div>
                                <div class="day2">Wed</div>
                                <div class="day2">Thu</div>
                                <div class="day2">Fri</div>
                                <div class="day2">Sat</div>
                    </div>
                    <div class="row days2">
                        <!-- Add days using Javscript -->
                    </div>
                </div>

                <div class="row m-2 justify-content-center">
                    <button class="button-62" id="runHybridPredictionBtn" role="button">Run Hybrid Prediction</button>   
                </div>

            </div> 
        </div>
<!-- Corn: November-March May-June -->
<!-- Rice: July-October -->
<!-- April offseason: no crop -->
        <div class="row loader">
            <div class="col">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center thresholds m-1 mt-4">
            <div class="col">
                <h3>Thresholds</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead  style="background-color: forestgreen; color: white">
                            <tr>
                                <th>Crop Name</th>
                                <th>Nitrogen</th>
                                <th>Phosphorus</th>
                                <th>Potassium</th>
                                <th>Air Temperature</th>
                                <th>Soil Temperature</th>
                                <th>Soil Moisture</th>
                                <th>Relative Humidity</th>
                                <th>Solar Radiation</th>
                                <th>Rainfall</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($thresholds as $threshold) : ?>
                                <tr>
                                    <td><?= $threshold['crop_name'] ?></td>
                                    <td><?= $threshold['min_nitrogen'] ?> - <?= $threshold['max_nitrogen'] ?></td>
                                    <td><?= $threshold['min_phosphorus'] ?> - <?= $threshold['max_phosphorus'] ?></td>
                                    <td><?= $threshold['min_potassium'] ?> - <?= $threshold['max_potassium'] ?></td>
                                    <td><?= $threshold['min_air_temp'] ?> - <?= $threshold['max_air_temp'] ?></td>
                                    <td><?= $threshold['min_soil_temp'] ?> - <?= $threshold['max_soil_temp'] ?></td>
                                    <td><?= $threshold['min_soil_moisture'] ?> - <?= $threshold['max_soil_moisture'] ?></td>
                                    <td><?= $threshold['min_rel_hum'] ?> - <?= $threshold['max_rel_hum'] ?></td>
                                    <td><?= $threshold['min_sol_rad'] ?> - <?= $threshold['max_sol_rad'] ?></td>
                                    <td><?= $threshold['min_rainfall'] ?> - <?= $threshold['max_rainfall'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    function updateProgress(progress) {
        // Update the progress bar width based on the progress value
        $(".loader .progress-bar").css("width", progress + "%");
        // Display the loading status below the loader
        $(".loader").append("<p>" + progress + "% completed...</p>");
    }

    $(document).ready(function () {
        // Define a function to handle the click event of the button
        $("#runMLPredictionBtn").click(function () {
            // Show the loader
            $(".loader .progress").show();
            // Use AJAX to send a request to run_machine_learning_script.php
            $.ajax({
                type: "GET",
                url: "run_machine_learning_script.php",
                success: function (response) {
                    // Handle the success response if needed
                    console.log("Machine Learning Prediction executed successfully");
                    // Hide the loader once the prediction is completed
                    $(".loader .progress").hide();
                },
                error: function (error) {
                // Handle the error response if needed
                    console.error("Error executing Machine Learning Prediction", error);
                    // Hide the loader in case of error
                    $(".loader .progress").hide();
                }
            });
        });

        // Define a function to handle the click event of the Hybrid button
        $("#runHybridPredictionBtn").click(function () {
            // Show the loader
            $(".loader .progress").show();
            // Use AJAX to send a request to run_python_script.php
            $.ajax({
                type: "GET",
                url: "run_python_script.php",
                success: function (response) {
                    // Handle the success response if needed
                    console.log("Hybrid Prediction executed successfully");
                    // Hide the loader once the prediction is completed
                    $(".loader .progress").hide();
                },
                error: function (error) {
                    // Handle the error response if needed
                    console.error("Error executing Hybrid Prediction", error);
                    // Hide the loader in case of error
                    $(".loader .progress").hide();
                }
            });
        });
    });


    
</script>




<script src="./js/troubleshoot/script.js"></script>
<script src="./js/troubleshoot/script2.js"></script>
<!-- Include jQuery -->

<!-- Include Bootstrap JS (Make sure this comes after jQuery) -->
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/script.js"></script>
</body>

</html>

<style>
    .box{
        
        background-color: #fefbd8;

    }
</style>