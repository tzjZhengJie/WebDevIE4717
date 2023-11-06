<?php
session_start();     // Purpose of this is to see the Welcome, username

@include 'config.php';

if(isset($_POST['order_btn'])) {

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $flat = $_POST['flat'];
   $street = $_POST['street'];
   $city = $_POST['city'];
   $state = $_POST['state'];
   $country = $_POST['country'];
   $postal_code = $_POST['postal_code'];

   $cart_query = mysqli_query($conn_user, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
   $price_total = 0;
   if(mysqli_num_rows($cart_query) > 0){
      while($product_item = mysqli_fetch_assoc($cart_query)){
         $product_name[] = $product_item['name'] .' ('. $product_item['quantity'] .') ';
         $product_price = number_format($product_item['price'] * $product_item['quantity']);
         $price_total += $product_price;
      };
   };

   $total_product = implode(', ',$product_name);
   $detail_query = mysqli_query($conn_user, "INSERT INTO `orders`(user_id, name, number, email, method, flat, street, city, state, country, postal_code, total_products, total_price) VALUES('$user_id', '$name','$number','$email','$method','$flat','$street','$city','$state','$country','$postal_code','$total_product','$price_total')") or die('query failed');

   if($cart_query && $detail_query){

      //mercury and thunderbird portion//
      $receiver_query = mysqli_query($conn_user, "SELECT `email` FROM `user_form` WHERE user_id = '$user_id'");
      if ($receiver_query) {
         $receiver_data = mysqli_fetch_assoc($receiver_query);
         $receiver = $receiver_data['email']; // Get the email address from the query result
         $sender = 'f32ee@localhost'; //admin email
         
         $subject = 'Farmville order confirmed';
         $message = "Your orders have been processed.\n\n";
         $message .= "Total Products: " . $total_product . "\n";
         $message .= "Total Price: $" . number_format($price_total, 2) . "\n";
         $message .= "Payment Method: Cash On Delivery\n\n";
         $message .= "Your Details:\n";
         $message .= "Name: " . $name . "\n";
         $message .= "Number: " . $number . "\n";
         $message .= "Your Address Details:\n";
         $message .= "Flat: " . $flat . "\n";
         $message .= "Street: " . $street . "\n";
         $message .= "City: " . $city . "\n";
         $message .= "State: " . $state . "\n";
         $message .= "Country: " . $country . "\n";
         $message .= "Postal Code: " . $postal_code . "\n";

         $headers = 'From: ' . $sender . "\r\n" .
            'Reply-To: ' . $sender . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
         mail($receiver, $subject, $message, $headers, '-f' . $sender);
      } else {
         // Handle the case where the query fails
         echo "Error fetching receiver email.";
      }
      /////////////////////////////////

      echo "
      <div class='order-message-container'>
         <div class='message-container'>
            <h3>thank you for shopping!</h3>
            <div class='order-detail'>
               <span>".$total_product."</span>
               <span class='total'> Total: $".number_format($price_total, 2)."</span>
            </div>
            <div class='customer-details'>
               <p> your name : <span>".$name."</span> </p>
               <p> your number : <span>".$number."</span> </p>
               <p> your email : <span>".$email."</span> </p>
               <p> your address : <span>".$flat.", ".$street.", ".$city.", ".$state.", ".$country.", ".$postal_code."</span> </p>
               <p> your payment mode : <span>".$method."</span> </p>
               <p>receiver: <span>".$receiver."</span></p>
               <p>*pay when product arrives*</p>            
            </div>
               <a href='products.php' class='btn'>continue shopping</a>
            </div>
      </div>
      ";
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

<?php include 'header.php'; ?>

<div class="container">

<section class="checkout-form">

   <h1 class="heading">complete your order</h1>

   <form action="" method="post">

   <div class="display-order">
      <?php
         $select_cart = mysqli_query($conn_user, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
         $total = 0;
         $grand_total = 0;
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = number_format($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total = $total += $total_price;
      ?>
      <span><?= $fetch_cart['name']; ?> x<?= $fetch_cart['quantity']; ?></span>
      <?php
         }
      }else{
         echo "<div class='display-order'><span>your cart is empty!</span></div>";
      }
      ?>
      <span class="grand-total"> Grand Total : $<?= number_format($grand_total, 2); ?> </span>
   </div>

      <div class="flex">
         <div class="inputBox">
            <span>Your Name</span>
            <input type="text" placeholder="E.g. David" name="name" required>
         </div>
         <div class="inputBox">
            <span>Your Number</span>
            <input type="number" placeholder="E.g. +6512345678" name="number" required>
         </div>
         <div class="inputBox">
            <span>Your Email</span>
            <input type="email" placeholder="E.g. hello@gmail.com" name="email" id="email" onchange="validateEmail()" required>
         </div>
         <div class="inputBox">
            <span>Payment Method</span>
            <input name="method" value="Cash On Delivery" style="background-color: #d3d3d3;" readonly>
         </div>
         <div class="inputBox">
            <span>Address Line 1</span>
            <input type="text" placeholder="E.g. Street address" name="flat" required>
         </div>
         <div class="inputBox">
            <span>Address Line 2</span>
            <input type="text" placeholder="E.g. Unit no." name="street">
         </div>
         <div class="inputBox">
            <span>City</span>
            <input type="text" placeholder="Leave blank if not applicable" name="city">
         </div>
         <div class="inputBox">
            <span>State</span>
            <input type="text" placeholder="Leave blank if not applicable" name="state">
         </div>
         <div class="inputBox">
            <span>Country</span>
            <input type="text" placeholder="E.g. Singapore" name="country" required>
         </div>
         <div class="inputBox">
            <span>Postal Code</span>
            <input type="number" placeholder="E.g. 123456" name="postal_code" required>
         </div>
      </div>
      <input type="submit" value="Order Now" name="order_btn" class="btn">
   </form>

</section>

</div>


<script type="text/javascript" src="../js/script.js"></script>
   
</body>
</html>