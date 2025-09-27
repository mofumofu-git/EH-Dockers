<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Upload</title>
</head>
<body>
    <h2>Upload a File</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="userfile" required>
        <br><br>
        <input type="submit" value="Upload File">
    </form>
</body>
</html>

