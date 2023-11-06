<?php
    session_start();     // Purpose of this is to see the Welcome, username
    @include 'config.php';

    if(isset($_POST['submit'])){

    $username = mysqli_real_escape_string($conn_user, $_POST['username']);
    $pass = md5($_POST['password']);

    $select = " SELECT * FROM user_form WHERE username = '$username' && password = '$pass' ";

    $result = mysqli_query($conn_user, $select);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_array($result);

        if($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['username'];
            header('location:index.php');

        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['username'];
            header('location:index.php');
        }
        
    }else{
        $error[] = 'Incorrect username or password!';
    }

    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="form-container">

    <form action="" method="post">
        <h3>Login Now</h3>
        <?php
        if(isset($error)){
            foreach($error as $error){
                echo '<span class="error-msg">'.$error.'</span>';
            };
        };
        ?>
        <input type="text" name="username" required placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
        <input type="password" name="password" required placeholder="Password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
        <input type="submit" name="submit" value="Login Now" class="form-btn">
        <p>Don't have an account? <a href="register.php">Register Now</a></p>
    </form>

    </div>

</body>
</html>