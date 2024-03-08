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
                <input type="radio" class="btn-check" name="btnradio" id="picksensor1" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="btnradio1">Radio 1</label>

                <input type="radio" class="btn-check" name="btnradio" id="picksensor2" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio2">Radio 2</label>

                <input type="radio" class="btn-check" name="btnradio" id="picksensor3" autocomplete="off">
                <label class="btn btn-outline-primary" for="btnradio3">Radio 3</label>
            </div>
        </div>
        <div class="row sensor-reading">
            <div class="col welcome-column">
                <div class="row">
                    <h4>Welcome to Our Decision Support app</h4>    
                </div>
                <div class="row">
                    <span class="power">powered by BSCPE</span>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="sensor-label">Nitrogen</span>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    2
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="sensor-label">Phosphorus</span>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    2
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="sensor-label">Potassium</span>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    2
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="sensor-label">Temperature</span>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    2
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <span class="sensor-label">Moisture</span>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    2
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    Timestamp
                    </div>
                    <div class="col">
                    4
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-8 heatmap">
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
                    <?php include 'heatmap.php';?>
                </div>
            </div>
            <div class="col-sm-4 recommendations">
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
      background-color: #EDEDEC; /* Set a light background color for the body */
    }

    .main-content {
      border-radius: 10px; /* Add border radius to the main content */
      padding: 20px; /* Add some padding for better visual appearance */
    }

    .container .col, .col-sm-8, .col-sm-4 {
      background-color: #ffffff; /* Set a white background for the columns */
      border-radius: 8px; /* Add border radius to the columns */
      /* padding: 15px; Add some padding for better visual appearance */
      margin: 10px; /* Add margin to separate columns */
    }

    .Welcome {
      border-radius: 8px; /* Add border radius to the text container */
      padding: 15px; /* Add some padding for better visual appearance */
    }
    .main-content {
    margin-left: 250px; /* Same as the width of your sidebar */
    padding: 1em;
    }
    
    .recommendation-box {
        width: 50px; /* Adjust the width as needed */
        height: 30px; /* Adjust the height as needed */
        margin: 5px; /* Add margin for spacing between boxes */
    }


    @media screen and (max-width: 768px) {
        .main-content {
            margin-left: 0; /* On smaller screens, the sidebar could be hidden or toggleable */
        }
    }
    @media screen and (min-width: 768px) {
        .welcome-column {
            display: none;
        }
    }
  </style>