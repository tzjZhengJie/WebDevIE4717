<?php
   session_start();     // Purpose of this is to see the Welcome, username

   @include 'config.php';

   if(isset($_POST['add_product'])){
      $p_name = $_POST['p_name'];
      $p_price = $_POST['p_price'];
      $p_image = $_FILES['p_image']['name'];
      $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
      $p_image_folder = 'uploaded_img/'.$p_image;

      // Check if a product with the same name, price, and image exists
      $check_query = mysqli_query($conn_user, "SELECT * FROM `products` WHERE name = '$p_name' AND price = '$p_price' AND image = '$p_image'");
      
      if (mysqli_num_rows($check_query) > 0) {
         $message[] = 'The fruit has already been added.';
      } else {
         // If no matching record found, insert the new product
         $insert_query = mysqli_query($conn_user, "INSERT INTO `products` (name, price, image) VALUES ('$p_name', '$p_price', '$p_image')") or die('query failed');

         if ($insert_query) {
               move_uploaded_file($p_image_tmp_name, $p_image_folder);
               $message[] = 'Product Added Successfully';
         } else {
               $message[] = 'Could Not Add The Product';
         }
      }
   };

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      // Remove cart entries associated with the product
      $delete_cart_entries_query = mysqli_query($conn_user, "DELETE FROM `cart` WHERE product_id = $delete_id ") or die('cart entries delete failed');

      // Now, you can safely delete the product
      $delete_product_query = mysqli_query($conn_user, "DELETE FROM `products` WHERE id = $delete_id ") or die('product delete failed');
      if($delete_query){
         header('location:admin.php');
         $message[] = 'Product Has Been Deleted';
      }else{
         header('location:admin.php');
         $message[] = 'Product Could Not Be Deleted';
      };
   };

   if(isset($_POST['update_product'])){
      $update_p_id = $_POST['update_p_id'];
      $update_p_name = $_POST['update_p_name'];
      $update_p_price = $_POST['update_p_price'];
      $update_p_image = $_FILES['update_p_image']['name'];
      $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
      $update_p_image_folder = 'uploaded_img/'.$update_p_image;

      $update_query = mysqli_query($conn_user, "UPDATE `products` SET name = '$update_p_name', price = '$update_p_price', image = '$update_p_image' WHERE id = '$update_p_id'");

      if($update_query){
         move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
         $message[] = 'Product Updated Succesfully';
         header('location:admin.php');
      }else{
         $message[] = 'Product Could Not Be Updated';
         header('location:admin.php');
      };

   };

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

   
<?php

   if(isset($message)){
      foreach($message as $message){
         echo '<div class="message"><span>'.$message.'</span> <img src="../images/cross.png" alt="Close" onclick="this.parentElement.style.display = `none`;" style="width: 40px";  /></div>';
      };
   };

?>

<?php include 'header.php'; ?>

<div class="container">

<section>

<form action="" method="post" class="add-product-form" enctype="multipart/form-data">
   <h3>add a new product</h3>
   <select id="productSelect" name="p_name" class="box" required>
      <option disabled selected>Select a product to add</option>
      <option>Japan Ginpuku Watermelon</option>
      <option>Nagano Shine Muscat (500g)</option>
      <option>Yubari King Melon</option>
      <option>Hakata Amaou Strawberry (270g)</option>
      <option>Nagano Beni Banka Peach</option>
      <option>Aomori Fuji Apple (10pcs)</option>
      <option>Nagano Seedless Kyoho (500g)</option>
      <option>Yamakita Mandarin Mikan (500g)</option>
      <option>Fuyu Gaki Persimmon (4pcs)</option>
      <option value="addNew">...Add New Fruits...</option>
   </select>

   <div id="newFruitContainer" style="display: none;">
      <input type="text" name="new_fruit_name" placeholder="Enter a new fruit" class="box">
   </div>

   <input type="number" step="0.01" name="p_price" placeholder="Enter the product price" class="box" required>  <!-- input a step:"0.01" so that the input box allows me to write a float number -->
   <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
   <input type="submit" value="Add The Product" name="add_product" class="btn">
</form>

</section>

<section class="display-product-table">

   <table>

      <thead>
         <th></th>
         <th>Product</th>
         <th>Price</th>
         <th>Action</th>
      </thead>

      <tbody>
         <?php
         
            $select_products = mysqli_query($conn_user, "SELECT * FROM `products`");
            if(mysqli_num_rows($select_products) > 0){
               while($row = mysqli_fetch_assoc($select_products)){
         ?>

         <tr>
            <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
            <td><?php echo $row['name']; ?></td>
            <td>$<?php echo number_format($row['price'], 2); ?></td>
            <td>
               <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are your sure you want to delete this?');">Delete</a> 
               <a href="admin.php?edit=<?php echo $row['id']; ?>" class="option-btn">Update</a> 
            </td>
         </tr>

         <?php
            };    
            }else{
               echo "<div class='empty'>no product added</div>";
            };
         ?>
      </tbody>
   </table>

</section>

<section class="edit-form-container">

   <?php
   
   if(isset($_GET['edit'])){
      $edit_id = $_GET['edit'];
      $edit_query = mysqli_query($conn_user, "SELECT * FROM `products` WHERE id = $edit_id");
      if(mysqli_num_rows($edit_query) > 0){
         while($fetch_edit = mysqli_fetch_assoc($edit_query)){
   ?>

   <form action="" method="post" enctype="multipart/form-data">
      <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="200" alt="">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
      <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
      <input type="number" step="0.01" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
      <input type="file" class="box" required name="update_p_image" accept="image/png, image/jpg, image/jpeg">
      <input type="submit" value="Update the product" name="update_product" class="btn">
      <input type="reset" value="Cancel" id="close-edit" class="option-btn">
   </form>

   <?php
            };
         };
         echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
      };
   ?>

</section>

</div>


<script type="text/javascript" src="../js/script.js"></script>

</body>
</html>