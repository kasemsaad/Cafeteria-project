<?php
include 'connection.php';

$db = new db();
$conn = $db->get_connection();

if (!isset($_COOKIE['Email'])) {
   header("location:index.php");
 } elseif ($_COOKIE["role"] !== "User") {
   header("location:index.php"); ////////// home
 }

$customer_id = $_COOKIE['customer_id']; // For testing, replace with actual customer_id when using sessions



$message = [];

// Check if the order is already submitted
$stmt_order = $conn->prepare("SELECT order_id FROM orders WHERE customer_id = ? AND order_status = 'Pending'");
$stmt_order->execute([$customer_id]);
$existing_order = $stmt_order->fetch(PDO::FETCH_ASSOC);

if (!$existing_order) {
    // If no existing order, create a new order
    $stmt_create_order = $conn->prepare("INSERT INTO orders (customer_id, room_number, notes, order_status) VALUES (?, ?, ?, ?)");
    $stmt_create_order->execute([$customer_id, '123', '', 'Pending']);
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
               /*  $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $product_quantity, $product_price]); */
 
                $stmt = $conn->prepare("INSERT INTO cart (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $product_quantity, $product_price]);
 
                $message[] = 'Product added to cart!';
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
    //$stmt = $conn->prepare("UPDATE order_details SET quantity = ? WHERE order_id = ? AND product_id = ?");
    $stmt2 = $conn->prepare("UPDATE cart SET quantity = ? WHERE order_id = ? AND product_id = ?");

    // Bind parameters and execute
   // $stmt->execute([$update_quantity, $order_id, $product_id]);
    $stmt2->execute([$update_quantity, $order_id, $product_id]);
    
    $message[] = 'Order quantity updated successfully!';
}

if(isset($_GET['remove'])){
   $order_id = $_GET['remove'];
   $product_id = $_GET['product_id'];
   
   try {
       // Prepare the delete query using the correct column names for the primary keys
       //$stmt = $conn->prepare("DELETE FROM order_details WHERE order_id = ? AND product_id = ?");
       
       // Bind parameters and execute
       //$stmt->execute([$order_id, $product_id]);
       
       $stmt2 = $conn->prepare("DELETE FROM cart WHERE order_id = ? AND product_id = ?");
       
       // Bind parameters and execute
       $stmt2->execute([$order_id, $product_id]);
       
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
   $stmt = $conn->prepare("DELETE FROM cart");
   
   // Bind parameter and execute
   $stmt->execute();
   
   header('location: userMakeOrder.php');
   exit(); // Ensure script execution stops after redirection
}


if(isset($_GET['new_order'])){
   // Prepare the delete query
   $stmt = $conn->prepare("DELETE FROM orders WHERE customer_id = ? And order_id = ?");
   
   // Bind parameter and execute
   $stmt->execute([$customer_id], [$order_id]);
   
   header('location: userMakeOrder.php');
   exit(); // Ensure script execution stops after redirection
}

// Check if the "Submit Order" button is clicked
// Check if the "Submit Order" button is clicked
if (isset($_POST['submit_order'])) {
   try {
       // Update order status to "In Progress"
       $stmt_update_status = $conn->prepare("UPDATE orders SET order_status = 'In Progress' WHERE order_id = ?");
       $stmt_update_status->execute([$order_id]);

       // Move cart data to order_details table
       $stmt_move_cart_data = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) SELECT order_id, product_id, quantity, price FROM cart WHERE order_id = ?");
       $stmt_move_cart_data->execute([$order_id]);

       // Delete cart data after moving it to order_details
       $stmt_delete_cart_data = $conn->prepare("DELETE FROM cart");
       $stmt_delete_cart_data->execute();

       // Unset the order_id session variable to clear the shopping cart
       unset($_COOKIE['order_id']);

       // Redirect to prevent resubmission of form
       header('location:userMakeOrder.php?new_order_id=');
       exit();
   } catch (PDOException $e) {
       $message[] = 'Error submitting order: ' . $e->getMessage();
   }
}


?>

<?php
// Fetch customer information
$stmt = $conn->prepare("SELECT * FROM `customers` WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$fetch_customer = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping orders</title>
 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
   
<?php
if(!empty($message)){
   foreach($message as $msg){
      echo '<div class="message" onclick="this.remove();">'.$msg.'</div>';
   }
}
?>

<div class="navbar">
   <div class="navbar-left">
      <a href="userMakeOrder.php">Home</a>
      <a href="listUserOrders.php">My Orders</a>
   </div>
   <div class="row height d-flex justify-content-center align-items-center">
      <div class="col-md-6">
         <form method="GET" action="userMakeOrder.php" class="form">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control form-input" name="search" placeholder="Search products...">
            <input type="submit" value="Search" class="btn btn-primary">
         </form>
      </div>
   </div>
   <div class="navbar-right">
      <div class="user-info">

         <img src="images/<?php echo $fetch_customer['profile_image']; ?>" alt="User Photo">
         <span><?php echo $fetch_customer['name']; ?></span>
      </div>
      <a href="index.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
   </div>
</div>


<div class="container">

<div class="products">

   <h1 class="heading">Products</h1>

   <div class="box-container">

   <?php
      // Fetch products based on search query if present
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($search_query)) {
    $stmt = $conn->prepare("SELECT * FROM `products` WHERE product_name LIKE ?");
    $stmt->execute(["%" . $search_query . "%"]);
} else {
    // Fetch all products if no search query is provided
    $stmt = $conn->prepare("SELECT * FROM `products`");
    $stmt->execute();
}
      
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

<div class="table-responsive">
   <table class="table table-bordered table-striped">
      <thead class="thead-dark">
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
      $stmt = $conn->prepare("SELECT c.*, p.image, p.product_name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.order_id = ?");
      $stmt->execute([$order_id]);
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

<script>
   function updateOrderDetails() {
      var form = document.getElementById('orderForm');
      form.submit();
   }
</script>



<tr class="table-bottom">
   <td colspan="4" style="font-weight:bold">Grand Total :</td>
   <td style="font-weight:bold">$<?php echo $grand_total; ?>/-</td>
   <td><a href="userMakeOrder.php?delete_all" onclick="return confirm('Delete all from orders?');" class="delete-btn <?php echo ($grand_total > 0)?'':'disabled'; ?>">Delete All</a></td>
</tr>

<?php
// Calculate grand total (if not already calculated)
$grand_total = 0;
$stmt_total = $conn->prepare("SELECT SUM(price * quantity) AS grand_total FROM cart WHERE order_id = ?");
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
</div>

<div class="form-row">
   
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
   
</div>

   <div class="orders-btn">  
   <form method="post">
      <?php 
      $stmt_order_status = $conn->prepare("SELECT order_status FROM orders WHERE order_id = ?");
      $stmt_order_status->execute([$order_id]);
      $order_status_row = $stmt_order_status->fetch(PDO::FETCH_ASSOC);
      $order_status = $order_status_row['order_status'];
      if ($order_status === 'Pending'): ?>
         <input type="submit" name="submit_order" value="Confirm" class="btn">
      <?php endif; ?>
   </form>
   <div class="latest-order">

<h1 class="heading">Latest Order</h1>

<div class="box-container">

     <?php
     // Fetch the latest order for the customer
     $stmtl = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? AND order_status != 'Pending' ORDER BY created_at DESC LIMIT 1");
     $stmtl->execute([$customer_id]);
     $latest_order = $stmtl->fetch(PDO::FETCH_ASSOC);

     if ($latest_order) {
         // Fetch the products from the latest order
         $order_id = $latest_order['order_id'];
         $stmtl = $conn->prepare("SELECT p.* FROM products p JOIN order_details od ON p.product_id = od.product_id WHERE od.order_id = ?");
         $stmtl->execute([$order_id]);
         while ($fetch_productl = $stmtl->fetch(PDO::FETCH_ASSOC)) {
     ?>
             <form method="post" class="box" action="">
                 <img src="images/<?php echo $fetch_productl['image']; ?>" alt="">
                 <div class="name"><?php echo $fetch_productl['product_name']; ?></div>
             </form>
     <?php
         }
     } else {
         // No orders found for the customer
         echo "<p>No orders found for this customer.</p>";
     }
     ?>

</div>
</div>
   </div>

</div>

</div>

</body>
</html>