<?php 
  session_start(); // Ensure session is started
 
?>

<?php include_once "header.php"; ?>

<?php include_once "fetch_user.php";?>

<?php  
// Initialize user data variables
$first_name = "";
$last_name = "";
$eno_no = "";
$email = ""; 
$pass = ""; 

// Check if user data is available in session
if (isset($_SESSION['user_data'])) {
    // Get user data from session
    $user_data = $_SESSION['user_data'];
    $first_name = isset($user_data['first_name']) ? $user_data['first_name'] : "";
    $last_name = isset($user_data['last_name']) ? $user_data['last_name'] : "";
    $eno_no = isset($user_data['eno_no']) ? $user_data['eno_no'] : "";
    $email = isset($user_data['email']) ? $user_data['email'] : ""; 
    $pass = isset($user_data['password']) ? $user_data['password'] : ""; 
}
?>

<body>
  <div class="wrapper">
    <section class="form signup">
      <header>Realtime Chat App</header>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="name-details">
          <div class="field input">
            <label>First Name</label>
            <input type="text" name="fname" placeholder="First name" value="<?php echo htmlspecialchars($first_name); ?>" required>
          </div>
          <div class="field input">
            <label>Last Name</label>
            <input type="text" name="lname" placeholder="Last name" value="<?php echo htmlspecialchars($last_name); ?>" required>
          </div>
        </div>
        <div class="field input">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter new password" value="<?php echo htmlspecialchars($pass); ?>" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field image">
          <label>Select Image</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
        </div> 
        <div class="field button">
          <input type="submit" name="submit" value="Continue to Chat">
        </div>
      </form>
      <div class="link">Already signed up? <a href="login.php">Login now</a></div>
    </section>
  </div>
 
  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>
</body>
</html>
