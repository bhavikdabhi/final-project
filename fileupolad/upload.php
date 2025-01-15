<?php
session_start(); // Start the session

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

// Retrieve enrollment number from session
$enrollment_no = isset($_SESSION['user_data']['eno_no']) ? $_SESSION['user_data']['eno_no'] : "";

if (empty($enrollment_no)) {
    die("Enrollment number not found. Please log in.");
}

$allowed_extensions = array("jpg", "jpeg", "png", "pdf", "doc", "docx", "txt", "html", "css"); // Allowed file extensions
$errors = []; // Array to store any errors

if (isset($_FILES["files"])) {
    $file_count = count($_FILES["files"]["name"]);
    
    for ($i = 0; $i < $file_count; $i++) {
        $filename = $_FILES["files"]["name"][$i];
        $tempname = $_FILES["files"]["tmp_name"][$i];
        $filesize = $_FILES["files"]["size"][$i];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($filetype, $allowed_extensions)) {
            $errors[] = "File type $filetype is not allowed.";
        }

        if (empty($errors)) {
            // Generate a unique filename
            $newfilename = uniqid() . "." . $filetype;

            // Prepare data for insertion
            $sql = "INSERT INTO file_uploads (enrollment_no, filename, filesize, filetype, data) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            // Read file content
            $content = file_get_contents($tempname);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sssss", $enrollment_no, $filename, $filesize, $filetype, $content);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect after successful upload
                header('Location: in.php');
                exit; // Stop further execution after redirect
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

