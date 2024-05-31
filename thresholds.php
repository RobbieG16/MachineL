<?php
if (!isset($link)) {
    include 'config.php';
}

// Determine which table to query based on the selected crop
$crop = isset($_POST['crop']) ? $_POST['crop'] : 'rice';

// Set the query based on the selected crop
if ($crop === 'corn') {
    $query = "SELECT stage, nitrogen, min_phosphorus, phosphorus, min_potassium, potassium, min_air_temp, air_temp, soil_temp, min_soil_moisture, soil_moisture, min_rel_hum, rel_hum, min_sol_rad, sol_rad, min_rainfall, rainfall FROM corn_threshold";
} else {
    $query = "SELECT stage, nitrogen, phosphorus, potassium, min_air_temp, air_temp, soil_temp, min_soil_moisture, soil_moisture, min_rel_hum, rel_hum, min_sol_rad, sol_rad, min_rainfall, rainfall FROM rice_threshold";
}

$result = mysqli_query($link, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($link));
}

$thresholds = [];
while ($row = mysqli_fetch_assoc($result)) {
    $thresholds[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threshold</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <script>
        function updateStages() {
            var crop = document.getElementById('crop').value;
            var stages = {
                corn: ['Seed', 'Early Germination', 'Late Germination', 'Grain Filling', 'Maturity'],
                rice: ['Seed', 'Mid', 'Matured']
            };
            var stageSelect = document.getElementById('stage');
            stageSelect.innerHTML = '';
            stages[crop].forEach(function(stage) {
                var option = document.createElement('option');
                option.value = stage;
                option.text = stage;
                stageSelect.add(option);
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Threshold Table</h2>

        <!-- Form to select the crop type -->
        <form method="post" class="text-center mb-4">
            <label for="crop">Select Crop:</label>
            <select name="crop" id="crop" class="form-control d-inline-block w-auto" onchange="updateStages()">
                <option value="rice" <?php if ($crop === 'rice') echo 'selected'; ?>>Rice</option>
                <option value="corn" <?php if ($crop === 'corn') echo 'selected'; ?>>Corn</option>
            </select>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <table class="table table-responsive table-striped table-bordered text-center">
            <thead>
                <tr style="color: white; background-color: forestgreen">
                    <th scope="col">Stages</th>
                    <th scope="col">Nitrogen</th>
                    <th scope="col">Phosphorus</th>
                    <th scope="col">Potassium</th>
                    <th scope="col">Air Temperature</th>
                    <th scope="col">Soil Temperature</th>
                    <th scope="col">Soil Moisture</th>
                    <th scope="col">Relative Humidity</th>
                    <th scope="col">Solar Radiation</th>
                    <th scope="col">Rainfall</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($thresholds as $threshold): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($threshold['stage']); ?></th>
                        <td><?php echo htmlspecialchars($threshold['nitrogen']); ?></td>
                        <td>
                            <?php 
                            if ($crop === 'corn' && $threshold['stage'] === 'Grain Filling') {
                                echo htmlspecialchars($threshold['min_phosphorus']) . ' - ' . htmlspecialchars($threshold['phosphorus']);
                            } else {
                                echo htmlspecialchars($threshold['phosphorus']);
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if ($crop === 'corn' && $threshold['stage'] === 'Grain Filling') {
                                echo htmlspecialchars($threshold['min_potassium']) . ' - ' . htmlspecialchars($threshold['potassium']);
                            } else {
                                echo htmlspecialchars($threshold['potassium']);
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($threshold['min_air_temp']) . ' - ' . htmlspecialchars($threshold['air_temp']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['soil_temp']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['min_soil_moisture']) . ' - ' . htmlspecialchars($threshold['soil_moisture']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['min_rel_hum']) . ' - ' . htmlspecialchars($threshold['rel_hum']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['min_sol_rad']) . ' - ' . htmlspecialchars($threshold['sol_rad']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['min_rainfall']) . ' - ' . htmlspecialchars($threshold['rainfall']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
