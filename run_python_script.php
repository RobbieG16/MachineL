<?php
session_start();

// Increase maximum execution time to 300 seconds (5 minutes)
ini_set('max_execution_time', 300);

// Set error reporting and display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set memory limit to 1 GB
ini_set('memory_limit', '1024M');

echo "Executing PHP script...<br>";

// Specify the full path to the Python executable
$pythonCommand = 'C:\Users\ASUS\AppData\Local\Programs\Python\Python311\python.exe';

// Execute Python script and capture its output
$pythonScriptPath = "./Hybrid_prediction.py";
$pythonOutput = shell_exec("$pythonCommand $pythonScriptPath 2>&1");

// Display Python script output
echo "<pre>$pythonOutput</pre>";

// Extract total predicted yields using regex
$riceYield = 0;
$cornYield = 0;

if (preg_match('/Total Hybrid Predicted Rice Yield:\s+(\d+)/', $pythonOutput, $matches)) {
    $riceYield = $matches[1];
}

if (preg_match('/Total Hybrid Predicted Corn Yield:\s+(\d+)/', $pythonOutput, $matches)) {
    $cornYield = $matches[1];
}

// Store the results in session variables with namespacing
$_SESSION['hybrid_predictions']['HybridRiceYield'] = $riceYield;
$_SESSION['hybrid_predictions']['HybridCornYield'] = $cornYield;

// Store the current timestamp
$_SESSION['hybrid_predictions']['HybridLastRunTime'] = date("Y-m-d H:i:s");

echo "Stored in session: Total Hybrid Predicted Rice Yield: $riceYield, Total Hybrid Predicted Corn Yield: $cornYield<br>";
echo "Last Prediction Run: " . $_SESSION['hybrid_predictions']['HybridLastRunTime'] . "<br>";
?>
