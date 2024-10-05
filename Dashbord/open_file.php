<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "files";

try {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    throw new Exception("Connection failed: " . $conn->connect_error);
  }

  if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT filename, data FROM file_uploads WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameter to prevent SQL injection
    $stmt->bind_param("i", $fileId);

    // Execute statement and get results
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      // Retrieve file data
      $row = $result->fetch_assoc();
      $filename = $row['filename'];
      $data = $row['data'];

      // Get file extension from filename
      $file_extension = pathinfo($filename, PATHINFO_EXTENSION);

      // Map extension to content type (using array for clarity)
      $content_types = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'txt' => 'text/plain',
        'html' => 'text/plain',
        'css' => 'text/plain',
      ];

      // Set content type based on extension
      $content_type = isset($content_types[$file_extension]) ? $content_types[$file_extension] : 'application/octet-stream';

      // Set download headers
      header('Content-Type: ' . $content_type);
      header('Content-Disposition: inline; filename="' . $filename . '"');
      header('Content-Length: ' . strlen($data));

      // Output the file data
      echo $data;
      exit; // Stop further execution
    } else {
      // File not found in the database
      echo "File not found.";
    }
  } else {
    // File ID not provided
    echo "File ID not specified.";
  }
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
} finally {
  // Close database connection (always close)
  $conn->close();
}
?>
