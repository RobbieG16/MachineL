<?php
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
?>
