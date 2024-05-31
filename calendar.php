<?php
// Database connection is included here if needed for other purposes
include 'config.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Retrieve the results from the session
$machineRiceYield = isset($_SESSION['machine_predictions']['MachineRiceYield']) ? $_SESSION['machine_predictions']['MachineRiceYield'] : 'N/A';
$machineCornYield = isset($_SESSION['machine_predictions']['MachineCornYield']) ? $_SESSION['machine_predictions']['MachineCornYield'] : 'N/A';
$machineLastRunTime = isset($_SESSION['machine_predictions']['MachineLastRunTime']) ? $_SESSION['machine_predictions']['MachineLastRunTime'] : 'N/A';

$hybridRiceYield = isset($_SESSION['hybrid_predictions']['HybridRiceYield']) ? $_SESSION['hybrid_predictions']['HybridRiceYield'] : 'N/A';
$hybridCornYield = isset($_SESSION['hybrid_predictions']['HybridCornYield']) ? $_SESSION['hybrid_predictions']['HybridCornYield'] : 'N/A';
$hybridLastRunTime = isset($_SESSION['hybrid_predictions']['HybridLastRunTime']) ? $_SESSION['hybrid_predictions']['HybridLastRunTime'] : 'N/A';
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
            <div class="presto mb-4">
                <h5>Legends</h5>
                <div class="row text-center">
                    <div class="col">
                        <span>Maximum Yield Prediction</span>
                        <div class="row d-flex justify-content-center">
                            <div class="box " style="background-color: #228B22; max-width:50px;height:50px"> &nbsp </div>
                        </div>   
                    </div>
                    <div class="col">
                        <span>Average Yield Prediction</span>
                        <div class="row d-flex justify-content-center">
                            <div class="box " style="background-color: #39e664; max-width:50px;height:50px;"> &nbsp</div>
                        </div>
                    </div>
                    <div class="col">
                        Minimum Yield Prediction
                        <div class="row d-flex justify-content-center">
                            <div class="box " style="background-color: #F9EF97; max-width:50px;height:50px;"> &nbsp </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="output_yield">
            <h5>Yield Prediction</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Corn</th>
                                <th>Rice</th>
                                <th>Last Run Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Hybrid Algorithm</td>
                                <td><?php echo htmlspecialchars($hybridCornYield) . ' mg/kg'; ?></td>
                                <td><?php echo htmlspecialchars($hybridRiceYield) . ' mg/kg'; ?></td>
                                <td><?php echo htmlspecialchars($hybridLastRunTime); ?></td>
                            </tr>
                            <tr>
                                <td>Machine Learning</td>
                                <td><?php echo htmlspecialchars($machineCornYield) . ' mg/kg'; ?></td>
                                <td><?php echo htmlspecialchars($machineRiceYield) . ' mg/kg'; ?></td>
                                <td><?php echo htmlspecialchars($machineLastRunTime); ?></td>
                            </tr>
                        </tbody>
                    </table>`
                
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
                            <div class="icon-recommend text-center">
                                <div class="col">
                                    <h6>Recommended</h6>
                                    <img id="recommended-crop-icon1" src="" alt="" style="width: 50px; height: 40px;">
                                </div>
                            </div>
                            <div id="icon-input" class="icon-input text-center">
                                <div class="col">
                                    <h6>Input</h6>
                                    <img id="crop-icon" src="" alt="" style="width: 50px; height: 40px;">
                                </div>
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
                        <div class="day">Sun</div><div class="day">Mon</div><div class="day">Tue</div><div class="day">Wed</div><div class="day">Thu</div><div class="day">Fri</div><div class="day">Sat</div>
                    </div>
                    <div class="row days">
                        <!-- Add days using javascript -->
                    </div>
                </div>
                <div class="row justify-content-center">
                    <button class="button-62"id="runMLPredictionBtn" role="button">Run Machine Learning Prediction</button>
                </div>
            </div> 

            <div class="col">
                <h2>HYBRID ALGORITHM PREDICTION</h2>
                <div class="udarbe">
                    <div class="row">
                        <div class="header2 d-flex justify-content-between">
                            <div class="month2"></div>
                            <div class="icon-recommend text-center">
                                <div class="col">
                                    <h6>Recommended</h6>
                                    <img id="recommended-crop-icon2" src="" alt="" style="width: 50px; height: 40px;">
                                </div>
                            </div>
                            <div id="icon-input-2" class="icon-input-2 text-center ">
                                <div class="col">
                                    <h6>Input</h6>
                                    <img id="crop-icon-2" src="" alt="" style="width: 50px; height: 40px;">
                                </div>
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
                <div class="row m-2 mb-2 justify-content-center">
                    <button class="button-62" id="runHybridPredictionBtn" role="button">Run Hybrid Prediction</button>   
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
        $(".loader .progress-bar").css("width", progress + "%");
        $(".loader").append("<p>" + progress + "% completed...</p>");
    }

    $(document).ready(function () {
        $("#runMLPredictionBtn").click(function () {
            $(".loader .progress").show();
            $.ajax({
                type: "GET",
                url: "run_machine_learning_script.php",
                success: function (response) {
                    console.log("Machine Learning Prediction executed successfully");
                    $(".loader .progress").hide();
                },
                error: function (error) {
                    console.error("Error executing Machine Learning Prediction", error);
                    $(".loader .progress").hide();
                }
            });
        });

        $("#runHybridPredictionBtn").click(function () {
            $(".loader .progress").show();
            $.ajax({
                type: "GET",
                url: "run_python_script.php",
                success: function (response) {
                    console.log("Hybrid Prediction executed successfully");
                    $(".loader .progress").hide();
                },
                error: function (error) {
                    console.error("Error executing Hybrid Prediction", error);
                    $(".loader .progress").hide();
                }
            });
        });
    });
</script>

<!-- <script src="./js/calendar.js"></script> -->
<script src="./js/script1.js"></script> <!-- script for days of calendar 1 -->
<script src="./js/script2.js"></script> <!-- script for days of calendar 2 -->
<!-- Include jQuery -->
<!-- Include Bootstrap JS (Make sure this comes after jQuery) -->
<!-- <script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/script.js"></script> -->
</body>
</html>
