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

// Specify the full path to the Hybrid prediction Python script
$pythonScriptPath = "./Hybrid_prediction.py";

// Open a pipe to the Python process to capture its output in real-time
$descriptorspec = array(
   0 => array("pipe", "r"),
   1 => array("pipe", "w"),
   2 => array("pipe", "w")
);

$process = proc_open("$pythonCommand $pythonScriptPath", $descriptorspec, $pipes);

if (is_resource($process)) {
    // Read from the pipe until it's closed by the process
    while ($s = fgets($pipes[1])) {
        // Send progress information back to the client
        echo "<script>updateProgress('$s');</script>";
        flush(); // Flush the output buffer to send the content to the client immediately
    }
    fclose($pipes[1]);
    fclose($pipes[2]);

    // Close the process
    proc_close($process);
}

// Capture any remaining output after the process has finished
$pythonOutput = shell_exec("$pythonCommand $pythonScriptPath 2>&1");

// Display Python script output
echo "<pre>$pythonOutput</pre>";
?>