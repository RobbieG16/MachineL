<?php
if (!isset($link)) {
  include 'config.php';
}
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '2000-01-01';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d');

function fetchNitrogen($query) {
    global $link;
    $result = mysqli_query($link, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'reading_time' => strtotime($row['reading_time']) * 1000, // Convert to milliseconds for Highcharts
            'nitrogen' => (float)$row['nitrogen']
        ];
    }
    return $data;
}

$query1 = "SELECT reading_time, nitrogen FROM rawsensor1 WHERE reading_time BETWEEN '$from_date' AND '$to_date'";
$query2 = "SELECT reading_time, nitrogen FROM rawsensor2 WHERE reading_time BETWEEN '$from_date' AND '$to_date'";
$query3 = "SELECT reading_time, nitrogen FROM rawsensor3 WHERE reading_time BETWEEN '$from_date' AND '$to_date'";

$sensorData = [
    'sensor1' => fetchNitrogen($query1),
    'sensor2' => fetchNitrogen($query2),
    'sensor3' => fetchNitrogen($query3)
];

$dataJson = json_encode($sensorData);
?>

<div id="nitrogen-container" style="width:100%; height:400px;"></div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function initializeChart() {
            const data = <?php echo $dataJson; ?>;
            Highcharts.chart('nitrogen-container', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Nitrogen Levels Over Time'
                },
                xAxis: {
                    type: 'datetime',
                    title: {
                        text: 'Date/Time'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Nitrogen Level'
                    }
                },
                series: [
                    {
                        name: 'Sensor 1',
                        data: data.sensor1.map(point => [point.reading_time, point.nitrogen])
                    },
                    {
                        name: 'Sensor 2',
                        data: data.sensor2.map(point => [point.reading_time, point.nitrogen])
                    },
                    {
                        name: 'Sensor 3',
                        data: data.sensor3.map(point => [point.reading_time, point.nitrogen])
                    }
                ]
            });
        }

        // Initialize the chart when the page loads
        initializeChart();

        // Reset button event listener
        document.getElementById('reset-button').addEventListener('click', function () {
            document.getElementById('from-date').value = '<?php echo $from_date_default; ?>';
            document.getElementById('to-date').value = '<?php echo $to_date_default; ?>';
            document.getElementById('filter-form').submit();
        });

        // Listen for form submit event and reinitialize chart after submission
        document.getElementById('filter-form').addEventListener('submit', function () {
            // Reinitialize the chart after form submission
            initializeChart();
        });
    });
</script>
