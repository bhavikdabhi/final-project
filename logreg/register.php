<?php
// Start session
session_start();
include("./db_config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $en_no = isset($_SESSION['user_data']['eno_no']) ? $_SESSION['user_data']['eno_no'] : ""; // Correctly fetching eno_no from session
    $password = trim(($_POST['password'])); // Hash the password

    // Debug: Output session values
    echo "Debug: Session Enrollment Number: $en_no<br>";

    // Check if enrollment number already exists
    $checkeno_no = "SELECT * FROM users WHERE eno_no = ?";
    $stmt = $conn->prepare($checkeno_no);
    $stmt->bind_param("s", $en_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Enrollment Number already exists!";
    } else {
        // Insert new user
        $ran_id = rand(time(), 100000000);
        $status = "Active now";
        $insertQuery = "INSERT INTO users (first_name, last_name, eno_no, email, password, unique_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        if (!$stmt) {
            die("Error in prepare: " . $conn->error);
        }
        $stmt->bind_param("ssssss", $firstName, $lastName, $en_no, $email, $password, $ran_id);
        if ($stmt->execute()) {
            header("Location: log.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>
