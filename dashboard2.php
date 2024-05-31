<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $_SESSION['selected_crop'] = $_POST['crop'];
        $_SESSION['selected_stage'] = $_POST['stage'];
    }
}

$selected_crop = isset($_SESSION['selected_crop']) ? $_SESSION['selected_crop'] : 'rice';
$selected_stage = isset($_SESSION['selected_stage']) ? $_SESSION['selected_stage'] : 'Early';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <!-- <link rel="stylesheet" href="./css/main.css"> -->
  <link rel="shortcut icon" href="./img/l1.gif" type="image/x-icon">
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Dashboard</h2>
    <div class="container">
        <div class="row">
            <div class="col">
                <?php include 'thresholds.php'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4>Select Crop and Stage:</h4>
                <form action="" method="post">
                    <div class="row">
                        <div class="col">
                            <label for="crop" class="form-label">Crop:</label>
                            <select name="crop" id="crop" class="form-select" onchange="updateStages()">
                                <option value="rice" <?= $selected_crop == 'rice' ? 'selected' : '' ?>>Rice</option>
                                <option value="corn" <?= $selected_crop == 'corn' ? 'selected' : '' ?>>Corn</option>
                                <!-- Add more options for other crops if needed -->
                            </select>
                        </div>
                        <div class="col">
                            <label for="stage" class="form-label">Stage:</label>
                            <select name="stage" id="stage" class="form-select">
                                <!-- Stages will be populated dynamically using JavaScript -->
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" name="submit" class="btn btn-primary mt-3">Set Threshold</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <?php include 'nitrogenlevel.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'phosphoruslevel.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'potassiumlevel.php'; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <?php include 'SoilMoisturelevel.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'SoilTemplevel.php'; ?>
            </div>
            <div class="col-sm">
                <?php include 'AirTemplevel.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php mysqli_close($link); ?>

<script>
    const cropStages = {
        rice: ["Early", "Mid", "Matured"],
        corn: ["Seed", "Early Germination", "Late Germination", "Grain Filling", "Maturity"]
    };

    function updateStages() {
        const cropSelect = document.getElementById('crop');
        const stageSelect = document.getElementById('stage');
        const selectedCrop = cropSelect.value;
        const stages = cropStages[selectedCrop];

        stageSelect.innerHTML = '';
        stages.forEach(stage => {
            const option = document.createElement('option');
            option.value = stage;
            option.text = stage;
            if (stage === '<?= $selected_stage ?>') {
                option.selected = true;
            }
            stageSelect.appendChild(option);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateStages();
    });
</script>

</body>
</html>

<style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic');
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
        background-color: #EDEDEC;
    }
    .main-content {
        margin-left: 250px;
        padding: 1em;
    }
    .container .col, .col-sm-8, .col-sm-4 {
        background-color: #ffffff;
        border-radius: 8px;
        margin: 10px;
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
