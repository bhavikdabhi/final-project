<?php
// Start session
session_start();
include("./db_config.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$en_no = isset($_SESSION['user_data']['eno_no']) ? $_SESSION['user_data']['eno_no'] : "";

if (isset($_POST['update_profile'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];

    $update_image = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_type = $_FILES['update_image']['type'];
    
    // Fetch current user data
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE eno_no = '$en_no'") or die('Query failed');
    if (mysqli_num_rows($select) > 0) {
        $fetch = mysqli_fetch_assoc($select);
        $old_pass = trim($fetch['password']);
    }

    $update_pass = !empty(trim($_POST['update_pass'])) ? trim($_POST['update_pass']) : '';
    $new_pass = !empty(trim($_POST['new_pass'])) ? trim($_POST['new_pass']) : '';
    $confirm_pass = !empty(trim($_POST['confirm_pass'])) ? trim($_POST['confirm_pass']) : '';

    if (!empty($update_pass) && !empty($new_pass) && !empty($confirm_pass)) {
        if ($update_pass != $old_pass) {
            $message[] = 'Old password not matched!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'Confirm password not matched!';
        } else {
            mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE eno_no= '$en_no'") or die('Query failed');
            $message[] = 'Password updated successfully!';
        }
    }

    // Update image if provided
    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large!';
        } else {
            // Read the image content into a variable
            $image_data = file_get_contents($update_image);
            
            $stmt = $conn->prepare("UPDATE `users` SET avatar = ? WHERE eno_no = ?");
            $stmt->bind_param('bs', $null, $en_no);
            $stmt->send_long_data(0, $image_data); // Send the binary data
            
            if ($stmt->execute()) {
                $message[] = 'Image updated successfully!';
            }
            $stmt->close();
        }
    }

    // Update other user details
    $updateQuery = "UPDATE `users` SET first_name = ?, last_name = ?, email = ? WHERE eno_no = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Error in prepare: " . $conn->error);
    }
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $en_no);
    if ($stmt->execute()) {
        header("Location: ../Dashbord/");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   <!-- custom css file link -->
   <link rel="stylesheet" href="css/upprofile.css">
</head>
<body>
<div class="update-profile">
   <?php
   // Fetch current user data for display
   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE eno_no = '$en_no'") or die('Query failed');
   if (mysqli_num_rows($select) > 0) {
       $fetch = mysqli_fetch_assoc($select);
   }
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <?php
      if (empty($fetch['avatar'])) {
         echo '<img src="images/default-avatar.png">';
      } else {
         echo '<img src="data:image/jpeg;base64,' . base64_encode($fetch['avatar']) . '">';
      }
      if (isset($message)) {
         foreach ($message as $msg) {
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <div class="flex">
         <div class="inputBox">
            <span>First Name:</span>
            <input type="text" name="fName" value="<?php echo htmlspecialchars($fetch['first_name']); ?>" class="box">
            <span>Last Name:</span>
            <input type="text" name="lName" value="<?php echo htmlspecialchars($fetch['last_name']); ?>" class="box">
            <span>Your Email:</span>
            <input type="email" name="email" value="<?php echo htmlspecialchars($fetch['email']); ?>" class="box">
            <span>Update Your Pic:</span>
            <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
         </div>
         <div class="inputBox">
            <span>Old Password:</span>
            <input type="password" name="update_pass" placeholder="Enter previous password" class="box">
            <span>New Password:</span>
            <input type="password" name="new_pass" placeholder="Enter new password" class="box">
            <span>Confirm Password:</span>
            <input type="password" name="confirm_pass" placeholder="Confirm new password" class="box">
         </div>
      </div>
      <input type="submit" value="Update Profile" name="update_profile" class="btn">
      <a href="../Dashbord/" class="delete-btn">Go Back</a>
   </form>
</div>

<?php
$conn->close(); // Close the connection here
?>

</body>
</html>
