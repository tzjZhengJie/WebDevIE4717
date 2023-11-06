<?php
   session_start();     // Purpose of this is to see the Welcome, username

   @include 'config.php';
   if(isset($_SESSION['user_name']) || isset($_SESSION['admin_name'])) {

      // // Assuming logged in
      // if (isset($_SESSION['user_name'])) {
      //    $username = $_SESSION['user_name'];
      // } elseif (isset($_SESSION['admin_name'])) {
      //    $username = $_SESSION['admin_name'];
      // }
      // $user_id_result = mysqli_query($conn_user, "SELECT user_id FROM user_form WHERE username = '$username'");
      // $user_id_row    = mysqli_fetch_assoc($user_id_result);
      // $user_id        = $user_id_row['user_id'];

      // Fetch cart items for the specific user
      $select_cart = mysqli_query($conn_user, "SELECT * FROM cart WHERE user_id = '$user_id'");
      
      if(isset($_POST['update_update_btn'])) {
         $update_value = $_POST['update_quantity'];
         $update_id = $_POST['update_quantity_id'];
         // $update_quantity_query = mysqli_query($conn_user, "UPDATE cart SET quantity = '$update_value' WHERE id = '$update_id'");
         $update_quantity_query = mysqli_query($conn_user, "UPDATE cart SET quantity = '$update_value' WHERE id = '$update_id' AND user_id = '$user_id'");
         if($update_quantity_query) {
            header('location:cart.php');
         };
      };

      if(isset($_GET['remove'])) {
         $remove_id = $_GET['remove'];
         mysqli_query($conn_user, "DELETE FROM cart WHERE id = '$remove_id' AND user_id = '$user_id'");
         header('location:cart.php');
      };

      if(isset($_GET['delete_all'])) {
         mysqli_query($conn_user, "DELETE FROM cart WHERE user_id = '$user_id'");
         header('location:cart.php');
      };
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
      
<?php include 'header.php'; ?>

<div class="container">

<section class="shopping-cart">

   <h1 class="heading">Shopping Cart</h1>

   <table>

      <thead>
         <th></th>
         <th>Product</th>
         <th>Price</th>
         <th>Quantity</th>
         <th>Subtotal</th>
         <th>Action</th>
      </thead>

      <tbody>

         <?php 
         
         $select_cart = mysqli_query($conn_user, "SELECT * FROM cart WHERE user_id = '$user_id'");
         $grand_total = 0;
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
         ?>

         <tr>
            <td><img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td>$<?php echo number_format($fetch_cart['price'],2); ?></td>
            <td>
               <form action="" method="post">
                  <input type="hidden" name="update_quantity_id"  value="<?php echo $fetch_cart['id']; ?>" >
                  <input type="number" name="update_quantity" min="1"  value="<?php echo $fetch_cart['quantity']; ?>" >
                  <input type="submit" value="Update" name="update_update_btn">
               </form>   
            </td>
            <td>$<?php echo $sub_total_ui = number_format($sub_total, 2); ?></td>
            <td><a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Remove item from cart?')" class="delete-btn">Remove</a></td>       
         </tr>
         <?php
            $grand_total += $sub_total;  
            };
         };
         ?>
         <tr class="table-bottom">
            <td><a href="products.php" class="option-btn" style="margin-top: 0;">Continue shopping</a></td>
            <td colspan="3">Grand Total</td>
            <td>$<?php echo number_format($grand_total, 2); ?></td>
            <td><a href="cart.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn">Delete all </a></td>  
         </tr>

      </tbody>

   </table>

   <div class="checkout-btn">
      <a href="checkout.php" class="btn <?= ($grand_total >= 1)?'':'disabled'; ?>">Procced to checkout</a>
   </div>

</section>

</div>
   
<!-- custom js file link  -->
   <script src="js/script.js"></script>

   </body>
</html>

<!-- if user is not logged in, display this message -->
<?php
} else { 
    include 'header.php';
    ?>
    <div class="container">

        <section class="shopping-cart">

            <h1 class="heading">Shopping Cart</h1>

            <table>
                <thead>
                    <th></th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </thead>
                <!-- Add your table body here -->
            </table>
            <div class="checkout-btn">
            <a href="checkout.php" class="btn <?= ($grand_total >= 1)?'':'disabled'; ?>">Procced to checkout</a>
             </div>

        </section>

    </div>

    echo "
    <div class='order-message-container'>
    <div class='message-container'>
        <h3 style='margin-bottom:3rem;'>Please Proceed to log in!</h3>
        <a href='login.php' class='btn'>Log In</a>
    </div>
    </div>
    ";
<?php
}
?>
