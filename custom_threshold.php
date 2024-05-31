<?php
if (!isset($link)) {
    include 'config.php';
}

$cornMonths = [];
$riceMonths = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $crop = $_POST['crop'];
    $stage = $_POST['stage'];
    $parameters = $_POST['parameters'];
    $months = isset($_POST['months']) ? $_POST['months'] : [];

    $monthsString = implode(',', $months);

    $stmt = $link->prepare("INSERT INTO threshold_months (crop_name, months) VALUES (?, ?) ON DUPLICATE KEY UPDATE months = ?");
    $stmt->bind_param("sss", $crop, $monthsString, $monthsString);
    $stmt->execute();
    $stmt->close();

    if ($crop === 'corn') {
        $table = 'corn_threshold';
    } else if ($crop === 'rice') {
        $table = 'rice_threshold';
    }

    $fields = [];
    $values = [];
    $types = '';

    $param_map = [
        'nitrogen' => 'nitrogen',
        'minimum_phosphorus' => 'min_phosphorus',
        'phosphorus' => 'phosphorus',
        'minimum_potassium' => 'min_potassium',
        'potassium' => 'potassium',
        'minimum_air_temperature' => 'min_air_temp',
        'air_temperature' => 'air_temp',
        'soil_temperature' => 'soil_temp',
        'minimum_soil_moisture' => 'min_soil_moisture',
        'soil_moisture' => 'soil_moisture',
        'minimum_relative_humidity' => 'min_rel_hum',
        'relative_humidity' => 'rel_hum',
        'minimum_solar_radiation' => 'min_sol_rad',
        'solar_radiation' => 'sol_rad',
        'minimum_rainfall' => 'min_rainfall',
        'rainfall' => 'rainfall'
    ];

    foreach ($parameters as $key => $value) {
        if (!empty($value) || $value === '0') {
            $fields[] = $param_map[$key] . ' = ?';
            $values[] = $value;
            $types .= 'd';
        }
    }

    if (!empty($fields)) {
        $values[] = $stage;
        $types .= 's';

        $sql = "UPDATE $table SET " . implode(', ', $fields) . " WHERE stage = ?";

        $stmt = $link->prepare($sql);

        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Threshold values updated successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Error: ' . $stmt->error];
        }
        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'No parameters provided for update.'];
    }

    $link->close();

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode($response);
        exit;
    } else {
        echo '<div class="alert alert-' . ($response['success'] ? 'success' : 'danger') . '">' . $response['message'] . '</div>';
    }
} else {
    $stmt = $link->prepare("SELECT months FROM threshold_months WHERE crop_name = 'corn'");
    $stmt->execute();
    $stmt->bind_result($cornMonths);
    $stmt->fetch();
    $stmt->close();
    $cornMonths = $cornMonths ? explode(',', $cornMonths) : [];

    $stmt = $link->prepare("SELECT months FROM threshold_months WHERE crop_name = 'rice'");
    $stmt->execute();
    $stmt->bind_result($riceMonths);
    $stmt->fetch();
    $stmt->close();
    $riceMonths = $riceMonths ? explode(',', $riceMonths) : [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Threshold</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/main.css">
    
    <script>
        let cornMonths = <?php echo json_encode($cornMonths); ?>;
        let riceMonths = <?php echo json_encode($riceMonths); ?>;

        function updateStages() {
            var crop = document.getElementById('crop').value;
            var stages = {
                corn: ['Seed', 'Early Germination', 'Late Germination', 'Grain Filling', 'Maturity'],
                rice: ['Early', 'Mid', 'Matured']
            };
            var stageSelect = document.getElementById('stage');
            stageSelect.innerHTML = '';
            stages[crop].forEach(function(stage) {
                var option = document.createElement('option');
                option.value = stage;
                option.text = stage;
                stageSelect.add(option);
            });
            updateFields();
        }

        function updateFields() {
            var crop = document.getElementById('crop').value;
            var stage = document.getElementById('stage').value;
            var fields = {
                corn: {
                    'Seed': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall'],
                    'Early Germination': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall'],
                    'Late Germination': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall'],
                    'Grain Filling': ['Nitrogen', 'Minimum Phosphorus', 'Phosphorus', 'Minimum Potassium', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall'],
                    'Maturity': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall']
                },
                rice: {
                    'Early': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall'],
                    'Mid': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall'],
                    'Matured': ['Nitrogen', 'Phosphorus', 'Potassium', 'Minimum Air Temperature', 'Air Temperature',  'Soil Temperature', 'Minimum Soil Moisture', 'Soil Moisture', 'Minimum Relative Humidity', 'Relative Humidity', 'Minimum Solar Radiation', 'Solar Radiation', 'Minimum Rainfall', 'Rainfall']
                }
            };
            var parametersDiv = document.getElementById('parameters');
            parametersDiv.innerHTML = '';
            fields[crop][stage].forEach(function(field) {
                var div = document.createElement('div');
                div.className = 'form-group';

                var label = document.createElement('label');
                label.textContent = field + ':';
                div.appendChild(label);

                var input = document.createElement('input');
                input.type = 'number';
                input.name = 'parameters[' + field.toLowerCase().replace(/ /g, '_') + ']';
                input.className = 'form-control';
                div.appendChild(input);

                parametersDiv.appendChild(div);
            });
            disableUsedMonths();
        }

        function disableUsedMonths() {
            var crop = document.getElementById('crop').value;

            if (crop === 'corn') {
                disableMonths(riceMonths);
                enableMonths(cornMonths);
            } else if (crop === 'rice') {
                disableMonths(cornMonths);
                enableMonths(riceMonths);
            }
        }

        function disableMonths(months) {
            var monthsCheckboxes = document.getElementsByName('months[]');
            for (let i = 0; i < monthsCheckboxes.length; i++) {
                if (months.includes(monthsCheckboxes[i].value)) {
                    monthsCheckboxes[i].disabled = true;
                }
            }
        }

        function enableMonths(months) {
            var monthsCheckboxes = document.getElementsByName('months[]');
            for (let i = 0; i < monthsCheckboxes.length; i++) {
                if (months.includes(monthsCheckboxes[i].value)) {
                    monthsCheckboxes[i].disabled = false;
                    monthsCheckboxes[i].checked = true;
                }
            }
        }
    </script>
</head>
<body>
    <div class="container-fluid">
        <h5>Enter Threshold Values</h5>
        <form method="POST" action="custom_threshold.php">
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="crop">Crop:</label>
                        <select id="crop" name="crop" class="form-control col-md custom-dropdown" onchange="updateStages()" required>
                            <option value="" disabled selected>Select crop</option>
                            <option value="corn">Corn</option>
                            <option value="rice">Rice</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="stage">Stage:</label>
                        <select id="stage" name="stage" class="form-control col-md custom-dropdown" onchange="updateFields()" required>
                            <option value="" disabled selected>Select stage</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="parameters" class="col-md-6"></div>

            <div class="form-group">
                <label for="months">Months:</label>
                <div id="months" class="checkbox-list d-flex justify-content-around">
                    <label><input type="checkbox" name="months[]" value="0"> January</label>
                    <label><input type="checkbox" name="months[]" value="1"> February</label>
                    <label><input type="checkbox" name="months[]" value="2"> March</label>
                    <label><input type="checkbox" name="months[]" value="3"> April</label>
                    <label><input type="checkbox" name="months[]" value="4"> May</label>
                    <label><input type="checkbox" name="months[]" value="5"> June</label>
                    <label><input type="checkbox" name="months[]" value="6"> July</label>
                    <label><input type="checkbox" name="months[]" value="7"> August</label>
                    <label><input type="checkbox" name="months[]" value="8"> September</label>
                    <label><input type="checkbox" name="months[]" value="9"> October</label>
                    <label><input type="checkbox" name="months[]" value="10"> November</label>
                    <label><input type="checkbox" name="months[]" value="11"> December</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
