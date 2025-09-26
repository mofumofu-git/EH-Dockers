<?php
// Path to fake log file
$logfile = __DIR__ . "/logs/access.log";

// Ensure logs/ exists
if (!is_dir(__DIR__ . "/logs")) {
    mkdir(__DIR__ . "/logs", 0755, true);
}

// Append this request
$ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$time = date("d/M/Y:H:i:s");
$ua   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
file_put_contents($logfile, "$ip - - [$time] \"$ua\"\n", FILE_APPEND);
clearstatcache(true, $logfile);

// Execute the log and capture its FULL rendered output (text + payload output)
// while suppressing any warnings/notices/deprecations.
$__old_reporting = error_reporting(0);
$__old_display   = ini_get('display_errors');
ini_set('display_errors', '0');
set_error_handler(function () { return true; });

ob_start();
@include $logfile;            
?>

<!DOCTYPE html>
<html>
<head>
  <title>LFI Lab - Log Viewer</title>
  <style>
    body { background:#111; color:#0f0; font-family:monospace; margin:0; padding:20px; }
    .logbox { background:#000; padding:10px; border:1px solid #0f0; border-radius:4px; overflow:auto; max-height:90vh; }
    hr { border:0; border-top:1px solid #0f0; margin:12px 0; }
    h1 { color:#0ff; margin:0 0 8px; }
    .muted { color:#6f6; margin:0 0 12px; }
  </style>
</head>
<body>
  <h1>Welcome to the Lab</h1>
  <p class="muted">This page displays all recorded User-Agent strings.</p>
  <hr>
  <div class="logbox">
    <pre><?php echo $rendered; // INTENTIONALLY unescaped for the CTF behavior ?></pre>
  </div>
</body>
</html>

