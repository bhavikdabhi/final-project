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
    <title>File Uploader and Viewer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            margin-top: 50px;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .file-buttons button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin: 5px 0;
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            transition: background-color 0.3s;
        }
        .file-buttons button:hover {
            background-color: #218838;
        }
        .file-buttons i {
            margin-right: 10px;
        }
        .file-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .file-buttons button i {
            font-size: 1.2em;
        }
    </style>
    <script>
    function openFile(fileId) {
        window.location.href = "open_file.php?id=" + fileId;
    }
    </script>
</head>

<body>
    <div class="container">
        <h1>File Uploader</h1>
        
       

        <h2>Uploaded Files</h2>
        <div class="file-buttons">
            <?php displayFiles($conn); ?>
        </div>

        <?php mysqli_close($conn); ?>
    </div>
</body>

</html>
