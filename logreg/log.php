<?php
// Start session
session_start();

// Check if user data is available in session
if (isset($_SESSION['user_data'])) {
    // Get user data from session
    $user_data = $_SESSION['user_data'];
    $first_name = isset($user_data['first_name']) ? $user_data['first_name'] : "";
    $last_name = isset($user_data['last_name']) ? $user_data['last_name'] : "";
    $eno_no = isset($user_data['eno_no']) ? $user_data['eno_no'] : "";
    $email = isset($user_data['email']) ? $user_data['email'] : ""; 

   
} else {
    // Redirect to the login page if user data is not available
    header("Location: fetch_user.php");
    exit;
}
?>
<?php    include("./db_config.php");
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./css/logreg.css">
</head>
<body>
    <div class="container" id="signup" style="display:none;">
      <h1 class="form-title">Register</h1>
      <form method="post" action="register.php">
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="fName" id="fName" placeholder="First Name" value="<?php echo $first_name; ?>" required >
           <label for="fname">First Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" value="<?php echo $last_name; ?>" required>
            <label for="lName">Last Name</label>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" required>
            <label for="email">Email</label>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" value="" required>
            <label for="password">Password</label>
        </div>
       <input type="submit" class="btn" value="Sign Up" name="signUp">
      </form>
      <p class="or">
        ----------or--------
      </p>
      <div class="icons">
        <i class="fab fa-google"></i>
        <i class="fab fa-facebook"></i>
      </div>
      <div class="links">
        <p>Already Have Account ?</p>
        <button id="signInButton">Sign In</button>
      </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="loging.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="number" name="eno_no" id="eno_no" placeholder="eno_no" value="<?php echo $eno_no; ?>" required>
              <label for="email">Enrollment number</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
          </div>
          <p class="recover">
            <a href="#">Recover Password</a>
          </p>
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="or">
          ----------or--------
        </p>
        <div class="icons">
          <i class="fab fa-google"></i>
          <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
          <p>Don't have account yet?</p>
          <button id="signUpButton">Sign Up</button>
        </div>
      </div>
      <script src="./js/script.js"></script>
</body>
</html>
