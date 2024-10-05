<?php 
  session_start(); include_once "php/config.php";
 
  
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
    <section class="form login">
      <header>Realtime Chat App</header>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Continue to Chat">
        </div>
      </form>
      <div class="link">Not yet signed up? <a href="index.php">Signup now</a></div>
    </section>
  </div>
  
  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/login.js"></script>

</body>
</html>
