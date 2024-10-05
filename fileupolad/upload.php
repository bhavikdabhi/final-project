
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

// ... rest of your code using $conn for database operations ...



$allowed_extensions = array("jpg", "jpeg", "png", "pdf", "doc", "docx","txt","html","css"); // Allowed file extensions

$errors = []; // Array to store any errors

if (isset($_FILES["files"])) {
  $file_count = count($_FILES["files"]["name"]);
  
  for ($i = 0; $i < $file_count; $i++) {
    $filename = $_FILES["files"]["name"][$i];
    $tempname = $_FILES["files"]["tmp_name"][$i];
    $filesize = $_FILES["files"]["size"][$i];
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // ... (File size and extension checks as before)

    if (empty($errors)) {
      // Generate a unique filename
      $newfilename = uniqid() . "." . $filetype;
      
      // Prepare data for insertion
      $sql = "INSERT INTO file_uploads (filename, filesize, filetype, data) VALUES (?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);
      
      // Bind parameters to prevent SQL injection
  $cont = file_get_contents($tempname);
    mysqli_stmt_bind_param($stmt, "ssss" , $filename, $filesize, $filetype,$cont);
      

      if (mysqli_stmt_execute($stmt)) {
          // Upload successful
      header('Location: in.php');
      } else {
          echo "Error uploading file " . $filename . ": " . mysqli_error($conn) . "<br>";
      }
      
      mysqli_stmt_close($stmt);
    } else {
      foreach ($errors as $error) {
        echo $error . "<br>";
      }
    }
  }
}

mysqli_close($conn);
?>