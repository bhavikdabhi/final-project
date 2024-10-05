<?php
// Start session
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "errsolsy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


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
<?php

$port = isset($_GET['port']) ? intval($_GET['port']) : 8000; 

// Attempt to get the client's IP address
$ipAddress = $_SERVER['REMOTE_ADDR'];

if ($ipAddress === '::1') {
    $ipAddress = '127.0.0.1';
}
$redirectUrl = "http://$ipAddress:$port/"; 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link href="./output.css" rel="stylesheet">
    <title>ProjectHub</title>
   <style>.hidden {
  display: none;
}

.active {
  display: block;
}
</style>
</head>
<body>
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">ProjectHub</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="#">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="fileo.php">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Projects</span>
                </a>
            </li>
            <li> 
                <div>
                <button type="button" class="" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                 <a>
            <i class='bx bxs-message-dots'></i>
            <span class="text">Message</span>
                  </a>
                    </button>
                </div>
                
                <ul id="user-menu" class="hidden">
                    <!-- Dropdown menu, show/hide based on menu state -->
                    <li><a href="../ChatAppn/" class="" role="menuitem" tabindex="-1" id="user-menu-item-0"> <i class='bx bxs-message-dots'></i>Personal Chat</a></li>
                    <li><a href="http://127.0.0.1:8000/" class="" role="menuitem" tabindex="-1" id="user-menu-item-1"> <i class='bx bxs-message-dots'></i>Global Chat</a></li>
                </ul>


                                    
            </li>
        </ul>
        <ul class="side-menu">
            <li></li>
            <li>
                <a href="../homepage/" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
			<?php
   // Fetch current user data for display
   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE eno_no = '$en_no'") or die('Query failed');
   if (mysqli_num_rows($select) > 0) {
       $fetch = mysqli_fetch_assoc($select);
   }
   ?>
            <a href="../logreg/update_profile.php" class="profile">
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
            </a>
        </nav>

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
                <a href="../fileupolad/in.php" class="btn-download">
                    <i class='bx bxs-cloud-download'></i>
                    <span class="text">Upload Files</span>
                </a>
            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3>10020</h3>
                        <p>Students</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3>2834</h3>
                        <p>Professor</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-calendar-check'></i>
                    <span class="text">
                        <h3>2543</h3>
                        <p>Total Projects</p>
                    </span>
                </li>
            </ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Recent Projects</h3>
                        <i class='bx bx-search'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Date Order</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Orders data here -->
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Todos</h3>
                        <i class='bx bx-plus'></i>
                        <i class='bx bx-filter'></i>
                    </div>
                    <ul class="todo-list">
                        <!-- Todo items here -->
                    </ul>
                </div>
            </div>
        </main>
    </section>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var userMenuButton = document.getElementById('user-menu-button');
    var userMenu = document.getElementById('user-menu');

    userMenuButton.addEventListener('click', function() {
        userMenu.classList.toggle('hidden');
        userMenu.classList.toggle('active');
    });

    // Add event listeners to each menu item
    var menuItems = userMenu.querySelectorAll('li');
    menuItems.forEach(function(item) {
        item.addEventListener('click', function() {
            // Remove 'active' class from all items
            menuItems.forEach(function(item) {
                item.classList.remove('active');
            });
            // Add 'active' class to the clicked item
            item.classList.add('active');
        });
    });

    document.addEventListener('click', function(event) {
        if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
            userMenu.classList.add('hidden');
            userMenu.classList.remove('active');
        }
    });
});


</script>

    <script src="script.js"></script>
</body>
</html>
