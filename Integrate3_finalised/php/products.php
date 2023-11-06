<?php
    session_start();     // Purpose of this is to see the Welcome, username
    @include 'config.php';

    if (isset($_SESSION['user_name']) || isset($_SESSION['admin_name'])) {

        if (isset($_POST['add_to_cart'])) {
            $product_name  = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_image = $_POST['product_image'];
            $product_quantity = 1;

            // Fetch product ID
            $product_id_result = mysqli_query($conn_user, "SELECT id FROM products WHERE name = '$product_name'");
            $product_id_row = mysqli_fetch_assoc($product_id_result);
            $product_id = $product_id_row['id'];
            
            // Check if the product is already in the cart
            $select_cart  = mysqli_query($conn_user, "SELECT * FROM cart WHERE image = '$product_image' AND name = '$product_name' AND price = '$product_price' AND user_id = '$user_id'");

            if (mysqli_num_rows($select_cart) > 0) {
                $message[] = 'Product already added to cart';   
            } else {
                $insert_product = mysqli_query($conn_user, "INSERT INTO cart(user_id, product_id, name, price, image, quantity) VALUES('$user_id','$product_id', '$product_name', '$product_price', '$product_image', '$product_quantity')");
                $message[] = 'Product added to cart successfully';
            }
            
        }
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Fruits</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php

    if(isset($message)){
    foreach($message as $message){
        echo '<div class="message"><span>'.$message.'</span> <img src="../images/cross.png" alt="Close" onclick="this.parentElement.style.display = `none`;" style="width: 40px"; ></div>';
    };
    };

?>

    <?php 
        include 'header.php'; 
    ?>

    <div class="container">

        <section class="products">

        <h1 class="heading">Our Fruits</h1>

        <div class="box-container">

            <?php
                $select_products = mysqli_query($conn_user, "SELECT * FROM `products`");
                if(mysqli_num_rows($select_products) > 0){
                    while($fetch_product = mysqli_fetch_assoc($select_products)){
            ?>

            <form action="" method="post">
                <div class="box">
                        <a href="sproduct-<?php echo strtolower($fetch_product['image']); ?>.php">      <!-- I tag the product detail page to the image name -->
                        <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="Fruit" style="cursor: pointer;">
                    </a>
                    <h3><?php echo $fetch_product['name']; ?></h3>
                    <div class="price">$<?php echo number_format($fetch_product['price'], 2); ?></div>
 
                    <!-- Below code is to track whether the fruit has already been added -->
                    <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                    <?php 
                        if (isset($_SESSION['user_name']) || isset($_SESSION['admin_name'])) {
                        // User is logged in, show "Add To Cart" button
                    ?>
                    <input type="submit" class="btn" value="Add To Cart" name="add_to_cart">
                    <?php
                        } else {
                    
                    ?>
                    <a href="login.php" class="btn">Log In to Add</a>
                    <?php
                        }
                    ?>
                </div>
            </form>

            <?php
                    };
                };
            ?>

        </div>

        </section>

    </div>

    <script src="../js/script.js"></script>

</body>
</html>