<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
    
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
</head>
<body>
<?php include 'sidebar.php'; ?>

<?php
// Calculate the default date range (current month)
$from_date_default = date('Y-m-01');
$to_date_default = date('Y-m-d');

// Get the selected dates or use the default dates
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : $from_date_default;
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : $to_date_default;
?>

<div class="main-content">
    <h2 class="mb-4">Analytics</h2>
    <div class="container">
        
        
        <form id="filter-form" method="GET">
            <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <label for="from-date">From:</label>
                                <input type="date" id="from-date" name="from_date" class="form-control" value="<?php echo $from_date; ?>" required>
                            </div>
                            <div class="col">
                                <label for="to-date">To:</label>
                                <input type="date" id="to-date" name="to_date" class="form-control" value="<?php echo $to_date; ?>" required>
                            </div>
                            <div class="col">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">Filter</button>
                            </div>
                            <div class="col">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-secondary form-control" id="reset-button">Reset</button>
                            </div>
                        </div>
                    </div>
            </div>
        </form>
        <div class="row">
            <div class="col-sm">
                <?php include 'nitrogen.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'potassium.php'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <?php include 'phosphorus.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'SoilMoisture.php'; ?>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm">
                <?php include 'SoilTemperature.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'AirTemperature.php'; ?>
            </div>
        </div>
        
    </div>
</div>
<?php mysqli_close($link); ?>
<script>
    document.getElementById('reset-button').addEventListener('click', function() {
        document.getElementById('from-date').value = '<?php echo $from_date_default; ?>';
        document.getElementById('to-date').value = '<?php echo $to_date_default; ?>';
        document.getElementById('filter-form').submit();
    });
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
      background-color: #EDEDEC; /* Set a light background color for the body */
    }

    .main-content {
    margin-left: 250px; /* Same as the width of your sidebar */
    padding: 1em;
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

    
    .recommendation-box {
        width: 50px; /* Adjust the width as needed */
        height: 30px; /* Adjust the height as needed */
        margin: 5px; /* Add margin for spacing between boxes */
    }

   
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0; 
        }
        .sidebar {
          width: auto;
        }
    }
    @media screen and (min-width: 768px) {
        .welcome-column {
            display: none;
        }
    }
  </style>