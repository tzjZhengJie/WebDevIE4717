<?php

   @include 'config.php';

   if(isset($_POST['submit'])){     // Checks if the form has been submitted

      $username = mysqli_real_escape_string($conn_user, $_POST['username']);
      $email = mysqli_real_escape_string($conn_user, $_POST['email']);
      $pass = md5($_POST['password']);
      $cpass = md5($_POST['cpassword']);     // md5 is to take a string as input and returns a 32-character hexadecimal number. Usually for hashing data for password
      $user_type = $_POST['user_type'];
      $admin_code = $_POST['admin_code'];

      $select = " SELECT * FROM user_form WHERE username = '$username' && email = '$email'";

      $result = mysqli_query($conn_user, $select);

      if(mysqli_num_rows($result) > 0) {

         $error[] = 'User Already Exist!';

      }else{
         if($user_type == 'admin') {
            if($admin_code == '123') { // admin code = 123
               $insert = "INSERT INTO user_form(username, email, password, user_type) VALUES('$username', '$email', '$pass', '$user_type')";
               mysqli_query($conn_user, $insert);
               header('location: login.php');
            } else {
               $error[] = 'Admin Code is Wrong!';
            }
         } elseif($pass != $cpass) {
            $error[] = 'Password Not Matched!';
         } else {           
            $insert = "INSERT INTO user_form(username, email, password, user_type) VALUES('$username', '$email', '$pass', '$user_type')";
            mysqli_query($conn_user, $insert);
            header('location: login.php');
         }
      }

   };

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

   <?php include 'header.php'; ?>

   <div class="form-container">

   <form action="" method="post" onsubmit="return validateEmail();">
      <h3>Register Page</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>

      <input type="text" name="username" id="username" required placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
      <input type="email" name="email" id="email" onkeyup="validateEmail()" required placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
      <span id="emailError" class="error-message"></span><br />
      <input type="password" name="password" required placeholder="Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
      <input type="password" name="cpassword" required placeholder="Confirm Password" value="<?php echo isset($_POST['cpassword']) ? $_POST['cpassword'] : ''; ?>">
      <select name="user_type" id="user_type">
         <option value="user">User</option>
         <option value="admin">Admin</option>
      </select>
      <input type="text" name="admin_code" id="admin-code" style="display: none;" placeholder="Key in admin code" value="<?php echo isset($_POST['admin_code']) ? $_POST['admin_code'] : ''; ?>">
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login.php">Proceed to login</a></p>
   </form>

</div>

<script type="text/javascript" src="../js/script.js"></script>
      
</body>
</html>