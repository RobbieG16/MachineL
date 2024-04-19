<?php
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
?>
