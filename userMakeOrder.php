<?php
include 'connection.php';

// Initialize PDO connection
$db = new db();
$conn = $db->get_connection();

// $user_id = $_SESSION['user_id']; // Uncomment this line if you're using session

$user_id = 1; // For testing, replace with actual user_id when using sessions

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Ensure script execution stops after redirection
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
    exit(); // Ensure script execution stops after redirection
}

if (isset($_POST['add_to_orders'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Using the insert_data method from the db class
    $cols = 'user_id, name, price, image, quantity';
    $values = [$user_id, $product_name, $product_price, $product_image, $product_quantity];
    $inserted = $db->insert_data('orders', $cols, $values);
    
    if ($inserted) {
        $message[] = 'Product added to orders!';
    } else {
        $message[] = 'Failed to add product to orders!';
    }
}

// Modify other parts of your script similarly to use the db class instance for database operations

if(isset($_POST['update_orders'])){
    $update_quantity = $_POST['orders_quantity'];
    $update_id = $_POST['orders_id'];
    
    // Prepare the update query
    $stmt = $conn->prepare("UPDATE orders SET quantity = :quantity WHERE id = :id");
    
    // Bind parameters and execute
    $stmt->execute(['quantity' => $update_quantity, 'id' => $update_id]);
    
    $message[] = 'orders quantity updated successfully!';
}

if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    
    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = :id");
    
    // Bind parameter and execute
    $stmt->execute(['id' => $remove_id]);
    
    header('location:index.php');
}

if(isset($_GET['delete_all'])){
    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = :user_id");
    
    // Bind parameter and execute
    $stmt->execute(['user_id' => $user_id]);
    
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping orders</title>

   <!-- Custom CSS file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php
if(isset($message)){
   foreach($message as $msg){
      echo '<div class="message" onclick="this.remove();">'.$msg.'</div>';
   }
}
?>

<div class="container">

<div class="user-profile">

   <?php
      // Prepare and execute a SELECT query using a prepared statement
      $stmt = $conn->prepare("SELECT * FROM `customers` WHERE customer_id = :user_id");
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      
      // Check if the query was successful and fetch user information
      if($stmt->rowCount() > 0){
         $fetch_user = $stmt->fetch(PDO::FETCH_ASSOC);
      };
   ?>

   <p> username : <span><?php echo $fetch_user['first_name']; ?></span> </p>
   <p> email : <span><?php echo $fetch_user['email']; ?></span> </p>
   <div class="flex">
      <a href="login.php" class="btn">login</a>
      <a href="register.php" class="option-btn">register</a>
      <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a>
   </div>

</div>

<div class="products">

   <h1 class="heading">latest products</h1>

   <div class="box-container">

   <?php
      // Prepare and execute a SELECT query using a prepared statement
      $stmt = $db->get_connection()->prepare("SELECT * FROM `products`");
      $stmt->execute();
      
      // Fetch products from the executed statement
      while($fetch_product = $stmt->fetch(PDO::FETCH_ASSOC)){
   ?>
      <form method="post" class="box" action="">
         <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_product['name']; ?></div>
         <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1">
         <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
         <input type="submit" value="add to orders" name="add_to_orders" class="btn">
      </form>
   <?php
      };
   
   ?>

   </div>

</div>

<div class="shopping-orders">

   <h1 class="heading">shopping orders</h1>

   <table>
      <thead>
         <th>image</th>
         <th>name</th>
         <th>price</th>
         <th>quantity</th>
         <th>total price</th>
         <th>action</th>
      </thead>
      <tbody>
      <?php
         // Prepare and execute a SELECT query using a prepared statement to retrieve orders items
         $stmt = $db->get_connection()->prepare("SELECT * FROM `orders` WHERE customer_id = :user_id");
         $stmt->bindParam(':user_id', $user_id);
         $stmt->execute();
         $grand_total = 0;
         // Fetch orders items from the executed statement
         while($fetch_orders = $stmt->fetch(PDO::FETCH_ASSOC)){
      ?>
         <tr>
            <td><img src="images/<?php echo $fetch_orders['image']; ?>" height="100" alt=""></td>
            <td><?php echo $fetch_orders['name']; ?></td>
            <td>$<?php echo $fetch_orders['price']; ?>/-</td>
            <td>
               <form action="" method="post">
                  <input type="hidden" name="orders_id" value="<?php echo $fetch_orders['id']; ?>">
                  <input type="number" min="1" name="orders_quantity" value="<?php echo $fetch_orders['quantity']; ?>">
                  <input type="submit" name="update_orders" value="update" class="option-btn">
               </form>
            </td>
            <td>$<?php echo $sub_total = ($fetch_orders['price'] * $fetch_orders['quantity']); ?>/-</td>
            <td><a href="index.php?remove=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('remove item from orders?');">remove</a></td>
         </tr>
      <?php
         $grand_total += $sub_total;
            }
         if($grand_total == 0){
            echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
         }
      ?>
      <tr class="table-bottom">
         <td colspan="4">grand total :</td>
         <td>$<?php echo $grand_total; ?>/-</td>
         <td><a href="index.php?delete_all" onclick="return confirm('delete all from orders?');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">delete all</a></td>
      </tr>
   </tbody>
   </table>

   <div class="orders-btn">  
      <a href="#" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

</div>

</div>

</body>
</html>
