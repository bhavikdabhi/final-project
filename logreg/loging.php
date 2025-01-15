<?php 
// Start session
session_start();
include("./db_config.php"); // Ensure this file has your database connection settings

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['signIn'])) {
    // Handle user login
    $eno_n = trim($_POST['eno_no']);  // Trim to remove any extra spaces
    $password = trim($_POST['password']); 

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE eno_no = ?");
    if (!$stmt) {
        die("Error in prepare: " . $conn->error);
    }
    $stmt->bind_param("s", $eno_n);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
       
        // Compare passwords
        if ($password === trim($row['password'])) {
            // Password is correct, start session and update status
            $_SESSION['eno_no'] = $eno_n;
            $status = "Active now";
            $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE eno_no = '{$row['eno_no']}'");
            if ($sql2) {
                $_SESSION['eno_no'] = $row['eno_no'];

                // Run the server.js file
                exec("node ./server.js > /dev/null 2>&1 &");

                // Redirect to update profile
                header("Location: ../Dashbord/");
                exit();
            } else {
                echo "Something went wrong. Please try again!";
            }
        } else {
            echo "Incorrect Password";
        }
    } else {
        echo "User not found";
    }

    $stmt->close();
}

$conn->close();
?>
