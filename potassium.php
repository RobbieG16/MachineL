<?php
if (!isset($link)) {
  include 'config.php';
}
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '2000-01-01';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

function fetchPotassium($query) {
    global $link;
    $result = mysqli_query($link, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'reading_time' => strtotime($row['reading_time']) * 1000, // Convert to milliseconds for Highcharts
            'potassium' => (float)$row['potassium']
        ];
    }
    return $data;
}

$query1 = "SELECT reading_time, potassium FROM rawsensor1 WHERE reading_time BETWEEN '$from_date' AND '$to_date'";
$query2 = "SELECT reading_time, potassium FROM rawsensor2 WHERE reading_time BETWEEN '$from_date' AND '$to_date'";
$query3 = "SELECT reading_time, potassium FROM rawsensor3 WHERE reading_time BETWEEN '$from_date' AND '$to_date'";

$sensorData = [
    'sensor1' => fetchPotassium($query1),
    'sensor2' => fetchPotassium($query2),
    'sensor3' => fetchPotassium($query3)
];

// mysqli_close($link);

$dataJson = json_encode($sensorData);
?>
<div id="potassium-container" style="width:100%; height:400px;"></div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const data = <?php echo $dataJson; ?>;
      Highcharts.chart('potassium-container', {
        chart: {
          type: 'line'
        },
        title: {
          text: 'Potassium Levels Over Time'
        },
        xAxis: {
          type: 'datetime',
          title: {
            text: 'Date/Time'
          }
        },
        yAxis: {
          title: {
            text: 'Potassium Level'
          }
        },
        series: [
          {
            name: 'Sensor 1',
            data: data.sensor1.map(point => [point.reading_time, point.potassium])
          },
          {
            name: 'Sensor 2',
            data: data.sensor2.map(point => [point.reading_time, point.potassium])
          },
          {
            name: 'Sensor 3',
            data: data.sensor3.map(point => [point.reading_time, point.potassium])
          }
        ]
      });
    });
</script>