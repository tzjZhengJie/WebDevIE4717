<?php
    @include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section class="background-image">
           
    </section>
    
    <header class="header">
        <div class="flex">
            <div class="logo">FARMVILLE</div>
            <nav class="navbar">
                <!-- Display Welcome, admin/user's username (admin/user) -->
                <a href="#" class="welcome-user">
                    Welcome, 
                    <?php 
                    if (isset($_SESSION['admin_name'])) {
                        echo $_SESSION['admin_name'] . " (Admin)";
                    } elseif (isset($_SESSION['user_name'])) {
                        echo $_SESSION['user_name'] . " (User)";
                    } else {
                        echo 'user'; // Default if no user is logged in
                    }
                    ?>
                </a>
                
                <a href="index.php">Home</a>

                <!-- Make sure only ADMIN can see the "Add Fruits" panel -->
                <?php if (isset($_SESSION['admin_name'])): ?>
                <a href="admin.php">Add/Edit Fruits</a>
                <?php endif; ?>

                <a href="products.php">View Fruits</a>

                <a href="contact.php">Contact Us</a>

                <!-- Check if either admin or user is logged in -->
                <?php if (isset($_SESSION['admin_name']) || isset($_SESSION['user_name'])): ?>
                    <!-- If either admin or user is logged in, show the "Logout" link -->
                    <a href="logout.php">Logout</a>
                <!-- If neither admin nor user is logged in, show the "Login" link -->
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
                
            </nav>

            <?php
                // Check if the user_type is either "user" or "admin"
                if (isset($_SESSION['admin_name']) || isset($_SESSION['user_name'] )) {
            
                    // Calculate the sum of the quantity column
                    $select_sum_quantity = mysqli_query($conn_user, "SELECT SUM(quantity) as total_quantity FROM cart WHERE user_id = '$user_id'") or die('query failed');
                    $row_quantity = mysqli_fetch_assoc($select_sum_quantity);
                    $total_quantity = ($row_quantity['total_quantity'] !== null) ? $row_quantity['total_quantity'] : 0;     // Check for null rows and display corresponding number

                    $select_sum_price = mysqli_query($conn_user, "SELECT SUM(price * quantity) as total_price FROM cart WHERE user_id = '$user_id'") or die('query failed');
                    $row_price = mysqli_fetch_assoc($select_sum_price);
                    $total_price = ($row_price['total_price'] !== null) ? $row_price['total_price'] : 0;        // Check for null rows and display corresponding number

                    // Echo the cart link HTML
                    echo '<a href="cart.php" class="cart">
                            <span class="price">$'  . number_format($total_price, 2) . '</span> 
                            View Cart 
                            <span class="quantity">' . $total_quantity . '</span>
                          </a>';
                } else {
                    // If user_type is neither "user" nor "admin", set quantities to 0 and echo the cart link HTML
                    $total_quantity = 0;
                    $total_price = 0;

                    echo '<a href="cart.php" class="cart">
                            <span class="price">$'  . number_format($total_price, 2) . '</span> 
                            View Cart 
                            <span class="quantity">' . $total_quantity . '</span>
                          </a>';
                }
            ?>

        </div>
    </header>
</body>
</html>

