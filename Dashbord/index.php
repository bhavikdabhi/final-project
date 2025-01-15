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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.7/css/boxicons.min.css">  
   
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet"> 
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="./js/chatbot.css">
    <title>ProjectHub</title>
    <style>
    .hidden {
    display: none;
}

.active {
    display: block;
}

.todo {
    background: #fff; /* Set to white for better readability */
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    position: relative;
   
}

.todo .head {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.todo ul {
    list-style-type: none;
    padding: 0;
    margin-top: 20px;
}

.todo ul li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.todo ul li .remove {
    color: red;
    cursor: pointer;
    font-size: 1.2rem;
}

.todo ul li.completed {
    text-decoration: line-through;
    color: #aaa;
}

/* Modal styles */
.modal-header,
.modal-body,
.modal-footer {
    border: none;
}

/* Blur effect for the background overlay */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5); /* Add transparency for a better effect */
    backdrop-filter: blur(5px); /* Adjust blur level */
    z-index: 5; /* Ensure it's below the modal */
    display: none; /* Initially hidden */
}
</style>
</head>

<body>
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile white'></i>
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
                <a href="../fileupolad/in.php">
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
                    <li><a href="../ChatAppn/" class="" role="menuitem" tabindex="-1" id="user-menu-item-0"> <i
                                class='bx bxs-message-dots'></i>Personal Chat</a></li>
                    <li><a href="http://127.0.0.1:8000/" class="" role="menuitem" tabindex="-1" id="user-menu-item-1">
                            <i class='bx bxs-message-dots'></i>Global Chat</a></li>
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
            <i class='bx bx-menu text-white'></i>
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
                <i class='bx bxs-bell text-white'></i>
                <!-- <span class="num">0</span> -->
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
                        echo '<div class="message">' . $msg . '</div>';
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

                        // Query to retrieve all uploaded files
                        $sql = "SELECT enrollment_no, filename, id FROM file_uploads";
                        $result = $conn->query($sql);

                        // Check if there are results
                        if ($result->num_rows > 0) {
                            echo '<table>
            <thead>
                <tr>
                    <th>Enrollment No</th>
                    <th>Files</th>
                </tr>
            </thead>
            <tbody>';

                            // Fetch and display each file
                            while ($row = $result->fetch_assoc()) {
                                $enrollment_no = htmlspecialchars($row['enrollment_no']);
                                $filename = htmlspecialchars($row['filename']);
                                $fileId = htmlspecialchars($row['id']);

                                echo "<tr>
                <td>{$enrollment_no}</td>
                <td>
                    <a href='open_file.php?id={$fileId}'>{$filename}</a>
                </td>
              </tr>";
                            }

                            echo '</tbody></table>';
                        } else {
                            echo '<p>No files found.</p>';
                        }

                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    } finally {
                        // Close the database connection
                        $conn->close();
                    }
                    ?>

                </div>
              

                <div class="overlay" id="blurOverlay"></div> <!-- Background blur overlay -->

<div class="todo">
    <div class="head">
        <h3>Todos</h3>
        <i class='bx bx-plus' id="addTodo" style="cursor: pointer;"></i>
        <i class='bx bx-filter' id="filterTodos" style="cursor: pointer;"></i>
    </div>
    <ul id="todoList"></ul>
</div>

<!-- Modal for Adding Todo -->
<div class="modal fade" id="todoModal" tabindex="-1" aria-labelledby="todoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="todoModalLabel">Add New Todo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="todoInput" class="form-control" placeholder="Enter your todo here...">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveTodo">Save Todo</button>
            </div>
        </div>
    </div>
</div>


            </div>

        </main>
    </section>
<section>
<button id="chatbot-toggler">
    <span class="ri-chat-4-line"></span>
    <span class="ri-close-line"></span>
  </button>
  <div class="chatbot-popup">
  <div class="chatbot">
    <!-- Header -->
    <div class="chatbot-header">
      <div class="header-content">
        <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" alt="Robot Logo" class="robot-icon">
        <span class="chatbot-name">Chatbot</span>
      </div>
      <button class="dropdown-button" id="close-chatbot">&#9662;</button>
    </div>

    <!-- Chat Body -->
    <div class="chatbot-body">
      <div class="message bot-message">
        <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" alt="Bot Icon" class="chat-icon">
        <div class="text-box">
          <p>Hey there 👋 <br> How can I help you today?</p>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="chatbot-footer">
      <input type="text" placeholder="Message..." class="message-input" required>
      <div class="footer-icons">
        <button type="button" class="emoji-button" id="emoji-picker">😊</button>
        <button type="submit" class="send-button" id="send-message">&#8593;</button>
      </div>
    </div>
  </div>
</div>

</section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var userMenuButton = document.getElementById('user-menu-button');
            var userMenu = document.getElementById('user-menu');

            userMenuButton.addEventListener('click', function () {
                userMenu.classList.toggle('hidden');
                userMenu.classList.toggle('active');
            });

            // Add event listeners to each menu item
            var menuItems = userMenu.querySelectorAll('li');
            menuItems.forEach(function (item) {
                item.addEventListener('click', function () {
                    // Remove 'active' class from all items
                    menuItems.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    // Add 'active' class to the clicked item
                    item.classList.add('active');
                });
            });

            document.addEventListener('click', function (event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                    userMenu.classList.remove('active');
                }
            });
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>

<script>
    // Get DOM elements
    const todoList = document.getElementById('todoList');
    const addTodoBtn = document.getElementById('addTodo');
    const saveTodoBtn = document.getElementById('saveTodo');
    const todoInput = document.getElementById('todoInput');
    const overlay = document.getElementById('blurOverlay'); // Reference to the overlay

    // Event listener for opening the modal
    addTodoBtn.addEventListener('click', () => {
        const todoModal = new bootstrap.Modal(document.getElementById('todoModal'));
        todoModal.show();
        overlay.style.display = 'block'; // Show overlay for blur effect
    });

    // Event listener for saving todos
    saveTodoBtn.addEventListener('click', () => {
        const todoText = todoInput.value.trim();
        if (todoText) {
            const li = document.createElement('li');
            li.innerHTML = `${todoText} <span class="remove">&times;</span>`;
            todoList.appendChild(li);
            todoInput.value = ''; // Clear input
            attachRemoveEvent(li);
            const todoModal = bootstrap.Modal.getInstance(document.getElementById('todoModal'));
            todoModal.hide(); // Close modal
            overlay.style.display = 'none'; // Hide overlay after saving
        }
    });

    // Function to attach event to remove button
    function attachRemoveEvent(li) {
        const removeBtn = li.querySelector('.remove');
        removeBtn.addEventListener('click', () => {
            todoList.removeChild(li);
        });
        
        // Toggle completed status
        li.addEventListener('click', () => {
            li.classList.toggle('completed');
        });
    }

    // Event listener for filtering todos
    document.getElementById('filterTodos').addEventListener('click', () => {
        const completedItems = todoList.querySelectorAll('.completed');
        if (completedItems.length > 0) {
            completedItems.forEach(item => item.style.display = item.style.display === 'none' ? 'flex' : 'none');
        } else {
            alert('No completed todos to filter.');
        }
    });

    // Event listener for closing the modal
    document.getElementById('todoModal').addEventListener('hidden.bs.modal', () => {
        overlay.style.display = 'none'; // Hide overlay when modal is closed
    });
</script>
    <script src="script.js"></script>
    <script src="./js/chatbot.js"></script>
</body>

</html>