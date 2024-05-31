<?php

$filename = 'interval_setting.txt';


// Function to convert hours to milliseconds
function hoursToMilliseconds($hours) {
    return $hours * 3600000;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adjustableTime'])) {
    $adjustableTime = intval($_POST['adjustableTime']);
    // Ensure the adjustable time is within the allowed range (1ms to 8 hours)
    $adjustableTime = max(1, min($adjustableTime, 28800000));
    file_put_contents($filename, $adjustableTime);

    header('Location: deploy.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['interval'])) {
    $interval = intval($_POST['interval']);
    $interval = max(60000, min($interval, 28800000));
    file_put_contents($filename, $interval);

    header('Location: deploy.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($filename)) {
        $currentInterval = intval(file_get_contents($filename));
    } else {
        // Default to 1 minute if not set
        $currentInterval = 60000;
    }
    echo $currentInterval;

    if (isset($_GET['default'])) {
        $defaultInterval = intval($_GET['default']);
        if ($defaultInterval >= 1 && $defaultInterval <= 8) {
            $currentInterval = hoursToMilliseconds($defaultInterval);
            file_put_contents($filename, $currentInterval);
        }
    }
}
?>
