<?php
    session_start();     // Purpose of this is to see the Welcome, username
    @include 'config.php';

    if(isset($_POST['submit'])) {     // Checks if the form has been submitted

        $name = mysqli_real_escape_string($conn_user, $_POST['name']);
        $email = mysqli_real_escape_string($conn_user, $_POST['email']);             
        $contact_message = $_POST['message'];
  
              
        $insert = "INSERT INTO contact_form(name, email, message) VALUES('$name', '$email', '$contact_message')";
        mysqli_query($conn_user, $insert);
        $message[] = 'Your form has been submitted successfully.';
    };
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php

   if(isset($message)){
      foreach($message as $message){
         echo '<div class="message"><span>'.$message.'</span> <img src="../images/cross.png" alt="Close" onclick="this.parentElement.style.display = `none`;" style="width: 50px";  /></div>';
      };
   };

?>

<?php include 'header.php'; ?>

<div class="form-container">

<form action="" method="post" onsubmit="return validateEmail();">
    <h3>Contact us</h3>
    <input type="text" id="name" name="name" placeholder="Name" required>
    <input type="email" name="email" id="email" placeholder="Email" required onkeyup="validateEmail()">
    <span id="emailError" class="error-message"></span><br />
    <textarea id="message" name="message" rows="4" placeholder="Type your message here..." required></textarea>
    <input type="submit" name="submit" value="Submit Form" class="form-btn">
    <p><i>We will get back to you as soon as possible</i></p>
</form>


</div>

<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>