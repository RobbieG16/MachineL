<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="./css/line.css">
    <link rel="stylesheet" href="./css/all.min.css">



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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .calendar-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .calendar, .calendar2 {
            margin: 10px; /* Adds space between calendars */
        }

        .calendar,
        .calendar2 {
            width: 100%;
            max-width: 600px;
            padding: 30px 20px;
            border-radius: 10px;
            background-color: var(--bg-color);
            margin-bottom: 20px;
        }

        .calendar .header,
        .calendar2 .header2 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ccc;
        }

        .calendar .header .month,
        .calendar2 .header2 .month2 {
            display: flex;
            align-items: center;
            font-size: 25px;
            font-weight: 600;
            color: var(--text-color);
        }

        .calendar .header .btns,
        .calendar2 .header2 .btns2 {
            display: flex;
            gap: 10px;
        }

        .calendar .header .btns .btn,
        .calendar2 .header2 .btns2 .btn2 {
            width: 50px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            color: #fff;
            background-color: var(--primary-color);
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .calendar .header .btns .btn:hover,
        .calendar2 .header2 .btns2 .btn2:hover {
            background-color: #db0933;
            transform: scale(1.05);
        }

        .weekdays,
        .weekdays2 {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .weekdays .day,
        .weekdays2 .day2 {
            width: calc(100% / 7 - 10px);
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        .days,
        .days2 {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .days .day,
        .days2 .day2 {
            width: calc(100% / 7 - 10px);
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 400;
            color: var(--text-color);
            background-color: #fff;
            transition: all 0.3s;
        }

        .days .day:not(.next):not(.prev):hover,
        .days2 .day2:not(.next2):not(.prev2):hover {
            color: #fff;
            background-color: var(--primary-color);
            transform: scale(1.05);
        }

        .days .day.today,
        .days2 .day2.today2 {
            color: #fff;
            background-color: var(--primary-color);
        }

        .days .day.next,
        .days .day.prev,
        .days2 .day2.next2,
        .days2 .day2.prev2 {
            color: #ccc;
        }
        .calendar-row {
            display: flex;
            justify-content: center;
        }
        .margin-top-custom {
    margin-top: -180px; /* You can adjust the value as needed */
    margin-left: -150px;
}
.udarbe {
  
    margin-left: -320px;
}
.carl{
    margin-bottom: -200px;
    margin-left: 150px;
}

        .calendar .legends{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ccc;
        }
        .legend-item, .calendar2 .thresholds-item{
            display: flex;
            align-items: center; /* Adjusted alignment to center items vertically */
            padding: 10px;
            font-size: 16px;
            font-weight: 600;
        }

        .legend-color {
            width: 40px;
            height: 40px;
            display: inline-block;
            margin-right: 10px; /* Added margin for spacing between color box and label */
        }
        .calendar2 .thresholds{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ccc;
        }
        .calendar2 z.margin-top-custom {
    margin-top: -180px; /* You can adjust the value as needed */
    margin-left: -150px;
}
    </style>
</head>

<body style="background-image: url('./image/b1.jpg'); background-size: contain; background-position: center;">
<?php include('sidebar.php'); ?> 



<div class="container">
 
<div class="carl">
    <?php include('progress.php'); ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="calendar margin-top-custom">
                    <div class="header">
                        <div class="month"></div>
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
                        <!-- lets add days using js -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="calendar margin-top-custom">
                    <div class="legends">
                        <h3>Legends</h3>
                    </div>
                    <!-- Add your legend items here -->
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: green; width:"></div>
                        <span class="legend-label">Best</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: yellow; width:"></div>
                        <span class="legend-label">Mid</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: red; width: "></div>
                        <span class="legend-label">Worst</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="calendar2 udarbe">
                    <div class="header2">
                        <div class="month2"></div>
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
                        <!-- lets add days using js -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="calendar2 margin-top-custom">
                    <div class="thresholds-item">
                        <h3>Thresholds</h3>
                    </div>
                    <!-- Add your legend items here -->
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
    <script src="./jquery-3.5.1.slim.min.js"></script>

    <script src="script.js"></script>
    <script src="script2.js"></script>
    <!-- Include jQuery -->
   
    <!-- Include Bootstrap JS (Make sure this comes after jQuery) -->
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>