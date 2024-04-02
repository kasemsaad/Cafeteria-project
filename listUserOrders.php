<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('location: login.php');//edit it !!!!!!!!!!!!!!!!!!!!
    exit();
}

// Initialize PDO connection
$db = new db();
$conn = $db->get_connection();

// Initialize variables
$from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
$orders = [];

// Fetch orders based on the specified date range
if (!empty($from_date) && !empty($to_date)) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? AND created_at BETWEEN ? AND ? AND order_status != 'Pending';");
    $stmt->execute([$_SESSION['customer_id'], $from_date, $to_date]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>List Orders</title>
   <!-- Custom CSS file link  -->
   <link rel="stylesheet" href="css/listUserOrders.css">
</head>
<body>

   <div class="user-container">
    <?php
    // Fetch customer name and image using customer_id
    $stmt = $conn->prepare("SELECT name, profile_image FROM customers WHERE customer_id = ?");
    $stmt->execute([$_SESSION['customer_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user data is retrieved successfully
    if ($user) {
        // Display user information
        ?>
        <div class="user-info">
            <p>Welcome, <?php echo $user['name']; ?></p>
            <!-- Add more user info here if needed -->
        </div>
        <div class="user-image">
            <img src="images/<?php echo $user['profile_image']; ?>" alt="User Image">
            <!-- Assuming profile_image is the path to the user's image -->
        </div>
    <?php } else {
        // Display a default message if user data is not found
        ?>
        <p>User data not found.</p>
    <?php } ?>


</div>
<div class="container">
   <h1>My Orders</h1>
   <!-- Form to specify date range -->
   <form method="post">
      <label for="from_date">From:</label>
      <input type="date" id="from_date" name="from_date" value="<?php echo $from_date; ?>">
      <label for="to_date">To:</label>
      <input type="date" id="to_date" name="to_date" value="<?php echo $to_date; ?>">
      <button type="submit">Filter</button>
   </form>

   <!-- Display orders in tabular format -->
   <table>
      <thead>
         <tr>
            <th>Order ID</th>
            <th>Order Date</th>
            <th>Order Status</th>
            <th>Total Amount</th>
            <th>Actions</th>
         </tr>
      </thead>
      <tbody>
      <?php $all_order_total=0;?>
   <?php foreach ($orders as $order): ?>
      <tr>
         <td><?php echo $order['order_id']; ?></td>
         <td><?php echo $order['created_at']; ?> <button class="toggle-details" data-order-id="<?php echo $order['order_id']; ?>">+</button></td>
         <td><?php echo $order['order_status']; ?></td>
         <?php $all_order_total+=$order['total_amount']; ?>
         <td>$<?php echo $order['total_amount']; ?></td>
         <?php if ($order['order_status'] === 'In Progress'): ?>
            <td>
                <button class="cancel-order" data-order-id="<?php echo $order['order_id']; ?>">Cancel</button>
            <?php endif; ?>
            </td>
         
      </tr>
      <!-- Add the following row for displaying product details -->
      <tr class="details" id="details-<?php echo $order['order_id']; ?>">
         <td colspan="5">
            <div class="order-details">
               <!-- Product details will be populated here -->
            </div>
         </td>
      </tr>
   <?php endforeach; ?>
</tbody>

   </table>
   <div style="text-align: right;padding-top: 20px;">

    <h2>Total : Egp <?php echo $all_order_total ?></h2>
    
   </div>
</div>

<script src="js/listUserOrders.js"></script>

</body>
</html>
