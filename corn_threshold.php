<?php
if (!isset($link)) {
    include 'config.php';
}

$query = "SELECT stage, nitrogen, phosphorus, potassium FROM corn_threshold";
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
    <title>Corn Threshold</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Corn Threshold</h2>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <tr style="color: white; background-color: forestgreen">
                    <th scope="col">Stages</th>
                    <th scope="col">Nitrogen</th>
                    <th scope="col">Phosphorus</th>
                    <th scope="col">Potassium</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($thresholds as $threshold): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($threshold['stage']); ?></th>
                        <td><?php echo htmlspecialchars($threshold['nitrogen']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['phosphorus']); ?></td>
                        <td><?php echo htmlspecialchars($threshold['potassium']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
