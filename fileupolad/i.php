<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "files";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Function to display files as buttons
function displayFiles($conn) {
  $sql = "SELECT id, filename FROM file_uploads";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      $filename = $row['filename'];
      echo "<button onclick='openFile($id)'>$filename</button> </br> "; // Call JavaScript function
      
    }
  } else {
    echo "No files found.";
  }

  mysqli_free_result($result);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <title>File Uploader and Viewer</title>
    <script>
    function openFile(fileId) {
        window.location.href = "open_file.php?id=" + fileId;
    }
    </script>
    <script>
    function upFile() {
        window.location.href = "upload.php";
    }
    </script>
</head>
<body>
<div class="wrapper">
    <header>File Uploader JavaScript</header>
    <form action="upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
        <input type="file" name="files[]" multiple class="file-input" id="files[]" hidden onchange="document.getElementById('submit-btn').click()">
        <i class="fas fa-cloud-upload-alt" onclick="document.getElementById('file').click()"></i>
        <p>Browse File to Upload</p>
        <input type="button" value="Upload" id="submit-btn" style="display: none;">
    </form>
    <section class="progress-area"></section>
    <section class="uploaded-area"></section>
    <div id="file-buttons">
        <?php //displayFiles($conn); ?>
    </div>
</div><script>
function submitForm() {
    document.getElementById('uploadForm').submit();
}
</script>
<?php
  // ... rest of your code using $conn for database operations ...

  mysqli_close($conn);
  ?>

<script src="js/main.js"></script>
</body>
</html>
