<?php
$ffa_status  = '';
$auth_status = '';
$output      = '';
$result      = null;

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ffa      = $_POST['ffa'] ?? '';

    if (!empty($ffa)) {
        $output = [];
        $return_var = 0;

        // File to search
        $filePath = __DIR__ . "/tmp/passwords.txt";

        // Build search string in format: username:password:ffa
        $searchString = "$username:$password:$ffa";

        // Grep command (seaerch for auth token)
	$command = "grep $searchString $filePath 2>/dev/null";
	
	// Run and capture all output
	$result = shell_exec($command);

	// Show result
	if (!empty($result)) {
    		echo "<b>Result:</b><br><pre>$result</pre>";
	} else {
		echo "<b>Result:</b><br><pre>No Matching Records</pre>";
	}
	#echo "<pre>Executed: $command</pre>";

    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demo of our Future Factor Authentication</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { font-size: 28px; }
        .form-container { margin-top: 20px; }
        input[type="text"], input[type="password"] {
            padding: 8px; margin-right: 8px; width: 180px;
        }
        input[type="submit"] { padding: 8px 16px; }
        .status { margin-top: 20px; font-size: 16px; }
        .status b { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Demo of our Future Factor Authentication</h1>
    <p>Lets see if we can grep your username, password and 2fa with our records</p>
    <form class="form-container" action="index.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="ffa" placeholder="FFA Code" required>
        <input type="submit" value="Login">
    </form>

    <div class="status">
	<p><b>FFA Status:</b> <?php echo htmlspecialchars($ffa_status); ?></p>
        <?php if (!empty($Output)): ?>
            <p><b>Result:</b></p>
            <pre><?php echo implode("\n", $output); ?></pre>
        <?php endif; ?>
        <p><b>Authentication Status:</b> <?php echo htmlspecialchars($auth_status); ?></p>
    </div>
</body>
</html>

