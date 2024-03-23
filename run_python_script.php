<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Output buffering is necessary for flushing the output progressively
ob_implicit_flush(true);
ob_end_flush();

echo "Executing PHP script...<br>";

// Specify the full path to the Python executable
$pythonCommand = 'C:\Users\Deso\AppData\Local\Programs\Python\Python312\python.exe';

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
