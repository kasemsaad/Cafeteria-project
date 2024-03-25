<?php
session_start(); // Start session if not already started
include 'connection.php';

// Initialize PDO connection
$db = new db();
$conn = $db->get_connection();

//$customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

$customer_id = 1; // For testing, replace with actual customer_id when using sessions


if (!isset($customer_id)) {
    header('location: login.php');
    exit();
}

if (isset($_GET['logout'])) {
    unset($_SESSION['customer_id']);
    session_destroy();
    header('location: login.php');
    exit();
}

$message = []; // Initialize an empty array to store messages

$stmt_order = $conn->prepare("SELECT order_id FROM orders WHERE customer_id = ? AND order_status = 'Pending'");
$stmt_order->execute([$customer_id]);
$existing_order = $stmt_order->fetch(PDO::FETCH_ASSOC);

if (!$existing_order) {
    // If no existing order, create a new order
    $stmt_create_order = $conn->prepare("INSERT INTO orders (customer_id, order_date, room_number, notes, order_status) VALUES (?, ?, ?, ?, ?)");
    $stmt_create_order->execute([$customer_id, $order_date, '123', '', 'Pending']);
    $order_id = $conn->lastInsertId();
} else {
    // If existing order, use that order ID
    $order_id = $existing_order['order_id'];
}

if (isset($_POST['add_to_orders'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    
 
    // Check if $order_id is defined
    if (!isset($order_id)) {
        // Inform the customer that there is no order created yet
        $message[] = 'No order created yet!';
    } else {
        try {
            // Check if the product already exists in the current order
            $stmt_check = $conn->prepare("SELECT * FROM order_details WHERE order_id = ? AND product_id = ?");
            $stmt_check->execute([$order_id, $product_id]);
            $existing_product = $stmt_check->fetch(PDO::FETCH_ASSOC);
 
            if ($existing_product) {
                // If product already exists, inform the customer
                $message[] = 'Product already exists in the order!';
            } else {
                // If product does not exist, add it to the order
                // Insert product details into order_details
                $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $product_quantity, $product_price]);
 
                $message[] = 'Product added to orders!';
            }
        } catch (PDOException $e) {
            // Handle the exception
            $message[] = 'Error adding product to orders: ' . $e->getMessage();
        }
    }
 }

if(isset($_POST['update_orders'])){
    $update_quantity = $_POST['orders_quantity'];
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    
    // Prepare the update query
    $stmt = $conn->prepare("UPDATE order_details SET quantity = ? WHERE order_id = ? AND product_id = ?");
    
    // Bind parameters and execute
    $stmt->execute([$update_quantity, $order_id, $product_id]);
    
    $message[] = 'Order quantity updated successfully!';
}

if(isset($_GET['remove'])){
   $order_id = $_GET['remove'];
   $product_id = $_GET['product_id'];
   
   try {
       // Prepare the delete query using the correct column names for the primary keys
       $stmt = $conn->prepare("DELETE FROM order_details WHERE order_id = ? AND product_id = ?");
       
       // Bind parameters and execute
       $stmt->execute([$order_id, $product_id]);
       
       $message[] = 'Item removed successfully from the order!';
   } catch (PDOException $e) {
       // Handle the exception
       $message[] = 'Error removing item from the order: ' . $e->getMessage();
   }
   
   header('location: userMakeOrder.php');
   exit(); // Ensure script execution stops after redirection
}
if(isset($_POST['update_order_details'])){
   $order_notes = $_POST['order_notes'];
   $room_number = $_POST['room_number'];
   
   try {
       // Update order details with the provided notes and room number
       $stmt_update_order = $conn->prepare("UPDATE orders SET notes = ?, room_number = ? WHERE order_id = ?");
       $stmt_update_order->execute([$order_notes, $room_number, $order_id]);
       
       $message[] = 'Order details updated successfully!';
   } catch (PDOException $e) {
       // Handle the exception
       $message[] = 'Error updating order details: ' . $e->getMessage();
   }
}



if(isset($_GET['delete_all'])){
    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM orders WHERE customer_id = ?");
    
    // Bind parameter and execute
    $stmt->execute([$customer_id]);
    
    header('location: userMakeOrder.php');
    exit(); // Ensure script execution stops after redirection
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
if(!empty($message)){
   foreach($message as $msg){
      echo '<div class="message" onclick="this.remove();">'.$msg.'</div>';
   }
}
?>

<div class="container">

<div class="customer-profile">

   <?php
      // Fetch customer information
      $stmt = $conn->prepare("SELECT * FROM `customers` WHERE customer_id = ?");
      $stmt->execute([$customer_id]);
      $fetch_customer = $stmt->fetch(PDO::FETCH_ASSOC);
   ?>

   <p> Customer Name : <span><?php echo $fetch_customer['first_name']; ?></span> </p>
   <p> Email : <span><?php echo $fetch_customer['email']; ?></span> </p>
   <div class="flex">
      <a href="login.php" class="btn">Login</a>
      <a href="register.php" class="option-btn">Register</a>
      <a href="userMakeOrder.php?logout=<?php echo $customer_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="delete-btn">Logout</a>
   </div>

</div>

<div class="products">

   <h1 class="heading">Latest Products</h1>

   <div class="box-container">

   <?php
      // Fetch products
      $stmt = $conn->prepare("SELECT * FROM `products`");
      $stmt->execute();
      
      while($fetch_product = $stmt->fetch(PDO::FETCH_ASSOC)){
   ?>
      <form method="post" class="box" action="">
         <img src="images/<?php echo $fetch_product['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_product['product_name']; ?></div>
         <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1">
         <input type="hidden" name="product_id" value="<?php echo $fetch_product['product_id']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
         <input type="hidden" name="product_name" value="<?php echo $fetch_product['product_name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
         <input type="submit" value="Add to Orders" name="add_to_orders" class="btn">
      </form>
   <?php
      }
   ?>

   </div>

</div>

<div class="shopping-orders">

   <h1 class="heading">Shopping Orders</h1>

   <table>
      <thead>
         <th>Image</th>
         <th>Name</th>
         <th>Price</th>
         <th>Quantity</th>
         <th>Total Price</th>
         <th>Action</th>
      </thead>
      <tbody>
   <?php
      // Fetch orders
      $stmt = $conn->prepare("SELECT od.*, p.image, p.product_name, p.price FROM order_details od JOIN products p ON od.product_id = p.product_id JOIN orders o ON od.order_id = o.order_id WHERE o.customer_id = ?");
      $stmt->execute([$customer_id]);
      $grand_total = 0;

      while($fetch_orders = $stmt->fetch(PDO::FETCH_ASSOC)){
   ?>
      <tr>
         <td><img src="images/<?php echo $fetch_orders['image']; ?>" height="100" alt=""></td>
         <td><?php echo $fetch_orders['product_name']; ?></td>
         <td>$<?php echo $fetch_orders['price']; ?>/-</td>
         <td>
            <form action="" method="post">
               <input type="hidden" name="order_id" value="<?php echo $fetch_orders['order_id']; ?>">
               <input type="hidden" name="product_id" value="<?php echo $fetch_orders['product_id']; ?>">
               <input type="number" min="1" name="orders_quantity" value="<?php echo $fetch_orders['quantity']; ?>">
               <input type="submit" name="update_orders" value="Update" class="option-btn">
            </form>
         </td>
         <td>$<?php echo $sub_total = ($fetch_orders['price'] * $fetch_orders['quantity']); ?>/-</td>
         <td>
            <a href="userMakeOrder.php?remove=<?php echo $fetch_orders['order_id']; ?>&product_id=<?php echo $fetch_orders['product_id']; ?>" class="delete-btn" onclick="return confirm('Remove item from orders?');">Remove</a>
         </td>
      </tr>
   <?php
      $grand_total += $sub_total;
      }
      if($grand_total == 0){
         echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">No item added</td></tr>';
      }
   ?>
<tr class="form-row">
   <td colspan="6">
      <form action="" method="post" id="orderForm">
         <label for="notes">Order Notes:</label>
         <textarea id="notes" name="order_notes" placeholder="Add order notes..." onchange="updateOrderDetails()"><?php echo !empty($_POST['order_notes']) ? htmlspecialchars($_POST['order_notes']) : ''; ?></textarea>
         <label for="roomlist">Room Number:</label>
         <select name="room_number" id="roomlist" onchange="updateOrderDetails()">
            <option value="">Choose a room</option>
            <?php
               // Fetch room numbers from the room table
               $stmt_rooms = $conn->prepare("SELECT room_no FROM rooms");
               $stmt_rooms->execute();
               while($row = $stmt_rooms->fetch(PDO::FETCH_ASSOC)){
                  $selected = ($row['room_no'] == $room_number) ? 'selected' : ''; // Check if this room number is currently selected
                  echo "<option value='".$row['room_no']."' $selected>".$row['room_no']."</option>";
               }
            ?>
         </select>
         <input type="hidden" name="update_order_details">
      </form>
   </td>
</tr>



<script>
   function updateOrderDetails() {
      var form = document.getElementById('orderForm');
      form.submit();
   }
</script>



<tr class="table-bottom">
   <td colspan="4">Grand Total :</td>
   <td>$<?php echo $grand_total; ?>/-</td>
   <td><a href="userMakeOrder.php?delete_all" onclick="return confirm('Delete all from orders?');" class="delete-btn <?php echo ($grand_total > 0)?'':'disabled'; ?>">Delete All</a></td>
</tr>


-<?php
// Calculate grand total (if not already calculated)
$grand_total = 0;
$stmt_total = $conn->prepare("SELECT SUM(price * quantity) AS grand_total FROM order_details WHERE order_id = ?");
$stmt_total->execute([$order_id]);
$grand_total_row = $stmt_total->fetch(PDO::FETCH_ASSOC);
if ($grand_total_row) {
    $grand_total = $grand_total_row['grand_total'];
}

// Update total_amount in orders table
$stmt_update_total = $conn->prepare("UPDATE orders SET total_amount = ? WHERE order_id = ?");
$stmt_update_total->execute([$grand_total, $order_id]);
?>
   
</tbody>

   </table>

   <div class="orders-btn">  
      <a href="#" class="btn <?php echo ($grand_total > 0)?'':'disabled'; ?>">Proceed to Checkout</a>
   </div>

</div>

</div>

</body>
</html>

