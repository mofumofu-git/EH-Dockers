<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['userfile'])) {
    $uploadDir = __DIR__ . "/uploads/";

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        echo "❌ Failed to create upload directory.";
        exit;
    }

    $origName = $_FILES['userfile']['name'] ?? '';
    $tmpPath  = $_FILES['userfile']['tmp_name'] ?? '';

    if ($_FILES['userfile']['error'] !== UPLOAD_ERR_OK) {
        echo "❌ Upload error (code " . $_FILES['userfile']['error'] . ")";
        exit;
    }

    // 1. Filename must contain allowed substrings
    $allowed = ['.jpg', '.jpeg', '.png', '.gif', '.pdf'];
    $nameLower = strtolower($origName);
    $hasAllowed = false;
    foreach ($allowed as $s) {
        if (strpos($nameLower, $s) !== false) {
            $hasAllowed = true;
            break;
        }
    }
    if (!$hasAllowed) {
        echo "❌ Filename must contain one of: " . implode(', ', $allowed);
        exit;
    }

    // 2. Filename must NOT contain ".php"
    if (strpos($nameLower, '.php') !== false) {
        echo "❌ Upload rejected: filename contains forbidden '.php'";
        exit;
    }

    // 3. Content must NOT contain ".php"
    $needle = '.php';
    $found = false;
    $overlap = '';
    $chunkSize = 8192;

    $handle = @fopen($tmpPath, 'rb');
    if ($handle === false) {
        echo "❌ Failed to open file for scanning.";
        exit;
    }

    while (!feof($handle)) {
        $chunk = fread($handle, $chunkSize);
        if ($chunk === false) break;
        $hay = strtolower($overlap . $chunk);
        if (strpos($hay, $needle) !== false) {
            $found = true;
            break;
        }
        $overlap = substr($hay, - (strlen($needle) - 1));
    }
    fclose($handle);

    if ($found) {
        echo "❌ Upload rejected: file content contains '.php'";
        exit;
    }

    // 4. Save safely
    $safeName = basename($origName);
    $target   = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeName;

    if (move_uploaded_file($tmpPath, $target)) {
        @chmod($target, 0644);
        echo "✅ File uploaded successfully: " . htmlspecialchars($safeName);
    } else {
        echo "❌ File upload failed.";
    }

} else {
    ?>
    <!doctype html>
    <html>
    <head><meta charset="utf-8"><title>Upload</title></head>
    <body>
      <h2>Upload a File</h2>
      <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="userfile" required>
        <button type="submit">Upload</button>
      </form>
    </body>
    </html>
    <?php
}

