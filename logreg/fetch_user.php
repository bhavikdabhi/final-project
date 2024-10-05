<?php

// Start session
session_start();

// Database connection parameters
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "users";
 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get enrollment number from the form
    $en_no = $_POST['en_no'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch user data based on enrollment number
    $sql = "SELECT * FROM tbl_users WHERE eno_no='$en_no'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();

        // Assign fetched data to PHP variables
        $first_name = isset($row['first_name']) ? $row['first_name'] : "";
        $last_name = isset($row['last_name']) ? $row['last_name'] : "";
        $eno_no = isset($row['eno_no']) ? $row['eno_no'] : "";
        $gender = isset($row['gender']) ? $row['gender'] : "";
        $bdate = isset($row['bdate']) ? $row['bdate'] : "";
        $bmonth = isset($row['bmonth']) ? $row['bmonth'] : "";
        $byear = isset($row['byear']) ? $row['byear'] : "";
        $email = isset($row['email']) ? $row['email'] : "";
        $branch = isset($row['branch']) ? $row['branch'] : "";
        $sem = isset($row['sem']) ? $row['sem'] : "";
        $city = isset($row['city']) ? $row['city'] : "";
        $street = isset($row['street']) ? $row['street'] : "";
        $zip = isset($row['zip']) ? $row['zip'] : "";
        $country = isset($row['country']) ? $row['country'] : "";
        $phone = isset($row['phone']) ? $row['phone'] : "";
        $adm_year = isset($row['adm_year']) ? $row['adm_year'] : "";
        $avatar = isset($row['avatar']) ? $row['avatar'] : "";
        $role = isset($row['role']) ? $row['role'] : "";
        $password = isset($row['password']) ? $row['password'] : ""; // Add password variable

        // Store user data in session
        $_SESSION['user_data'] = $row;

        // Redirect to login.php
        header("Location: ./log.php");
        exit; // Stop further execution
    } else {
        echo "User not found";
    }

    $conn->close();
}
?>
