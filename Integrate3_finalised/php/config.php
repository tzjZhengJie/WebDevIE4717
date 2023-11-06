<?php

// Connect shopping database
$conn_user = mysqli_connect('localhost','root','','f32ee') or die('connection failed');

// Assuming logged in, i want to set user_id as a primary key variable that is available for all files.
if (isset($_SESSION['user_name'])) {
    $username = $_SESSION['user_name'];
} elseif (isset($_SESSION['admin_name'])) {
    $username = $_SESSION['admin_name'];
}
else{
    //echo "Error: No user is logged in";
}
//fetch user_id 
$user_id_result = mysqli_query($conn_user, "SELECT user_id FROM user_form WHERE username = '$username'");
$user_id_row    = mysqli_fetch_assoc($user_id_result);
$user_id        = $user_id_row['user_id'];
?>
