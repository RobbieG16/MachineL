<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Executing PHP script...<br>";

// Specify the full path to the Python executable
$pythonCommand = 'C:\Users\ASUS\AppData\Local\Programs\Python\Python311\python.exe';

// Specify the full path to the machine learning Python script
$pythonScriptPath = "./predecting.py";

// Execute Python script and capture its output
$pythonOutput = shell_exec("$pythonCommand $pythonScriptPath 2>&1");

// Display Python script output
echo "<pre>$pythonOutput</pre>";

// Extract total predicted yields using regex
$riceYield = 0;
$cornYield = 0;

if (preg_match('/Total Predicted Rice Yield:\s+(\d+)/', $pythonOutput, $matches)) {
    $riceYield = $matches[1];
}

if (preg_match('/Total Predicted Corn Yield:\s+(\d+)/', $pythonOutput, $matches)) {
    $cornYield = $matches[1];
}

// Store the results in session variables with namespacing
$_SESSION['machine_predictions']['MachineRiceYield'] = $riceYield;
$_SESSION['machine_predictions']['MachineCornYield'] = $cornYield;

// Store the current timestamp
$_SESSION['machine_predictions']['MachineLastRunTime'] = date("Y-m-d H:i:s");

echo "Stored in session: Total Predicted Rice Yield: $riceYield, Total Predicted Corn Yield: $cornYield<br>";
echo "Last Prediction Run: " . $_SESSION['machine_predictions']['MachineLastRunTime'] . "<br>";
?>
