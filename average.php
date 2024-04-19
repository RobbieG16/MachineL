<?php
include 'config.php';

function getAverage($table, $column, $startDate, $endDate) {
    global $link;
    $sql = "SELECT AVG($column) AS average FROM $table WHERE reading_time BETWEEN '$startDate' AND '$endDate'";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['average'];
    } else {
        return 0; // Return 0 if there is no result or query error
    }
}

$columns = ['air_temp', 'soil_temp', 'soil_moisture', 'nitrogen', 'phosphorus', 'potassium'];

$lastWeekStartDate = date('Y-m-d', strtotime('-1 week'));
$lastWeekEndDate = date('Y-m-d');

$lastMonthStartDate = date('Y-m-d', strtotime('-1 month'));
$lastMonthEndDate = date('Y-m-d');

$averages = array();

foreach ($columns as $column) {
    $table_averages = array();
    for ($sensor = 1; $sensor <= 3; $sensor++) {
        $sensorName = 'rawsensor' . $sensor;
        $averageLastWeek = getAverage($sensorName, $column, $lastWeekStartDate, $lastWeekEndDate);
        $averageLastMonth = getAverage($sensorName, $column, $lastMonthStartDate, $lastMonthEndDate);

        $table_averages[$sensorName] = array('lastWeek' => $averageLastWeek, 'lastMonth' => $averageLastMonth);
    }

    // Calculate combined averages for each column
    $lastWeekAverages = array_column($table_averages, 'lastWeek');
    $lastMonthAverages = array_column($table_averages, 'lastMonth');
    $averageLastWeek = array_sum($lastWeekAverages) / count($lastWeekAverages);
    $averageLastMonth = array_sum($lastMonthAverages) / count($lastMonthAverages);

    $averages[$column] = array('lastWeek' => $averageLastWeek, 'lastMonth' => $averageLastMonth);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrient Averages</title>
</head>
<body>

<h2>Last Week and Last Month Nutrient Averages</h2>

<table border="1">
  <tr>
    <th>Nutrient</th>
    <th>Last Week Average</th>
    <th>Last Month Average</th>
  </tr>
<?php foreach ($averages as $column => $average): ?>
  <tr>
    <td><?php echo ucfirst(str_replace('_', ' ', $column)); ?></td>
    <td><?php echo number_format($average['lastWeek'], 2); ?></td>
    <td><?php echo number_format($average['lastMonth'], 2); ?></td>
  </tr>
<?php endforeach; ?>
</table>

</body>
</html>

<?php mysqli_close($link); ?>
