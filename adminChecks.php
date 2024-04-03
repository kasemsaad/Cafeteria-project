<?php
include 'connection.php';

if (!isset($_COOKIE['Email'])) {
    header("location:index.php");
  } elseif ($_COOKIE["role"] !== "Admin") {
    header("location:index.php"); ////////// home
  }

// Initialize PDO connection
$db = new db();
$conn = $db->get_connection();



// Fetch all users
$stmt = $conn->query("SELECT * FROM customers");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery library -->
   <title>List Orders</title>
   <!-- Custom CSS file link  -->
   <link rel="stylesheet" href="css/adminChecks.css">

</head>
<body>

    <?php
    // Fetch customer name and image using customer_id
    $stmt = $conn->prepare("SELECT name, profile_image FROM customers WHERE customer_id = ?");
    $stmt->execute([$_COOKIE['customer_id']]);
    $login_user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="navbar" style="background-color: #333; color: white; display: flex; justify-content: space-between; align-items: center; height: 56px;">
    <div class="navbar-left" style="margin-left:10px;">
        <a style="color: white;" href="Orders_checks.php">Home |</a>
        <a style="color: white;" href="viewAllProduct.php">Products |</a>
        <a style="color: white;" href="viewAllUsers.php">Users |</a>
        <a style="color: white;" href="userMakeOrder.php">Manual Order |</a>
        <a style="color: white;" href="adminChecks.php">Checks</a>
    </div>
    <div class="navbar-right">
        <div class="user-info" style="display: flex; align-items: center;">
            <img src="images/<?php echo $login_user['profile_image']; ?>" alt="User Photo" style="width: 40px; height: 40px; border-radius: 50%; margin-right:10px;">
            <span><?php echo $login_user['name'] ; ?></span>
            <a style="color: orange;margin-left:10px;" href="index.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </div>
</div>







 <!-- Form to specify date range -->



</div>
<div class="container">
   <h1>Checks</h1>
 <!-- Display users in tabular format -->
<!-- Main table -->
<form method="post">
    <label for="from_date">From:</label>
    <input type="date" id="from_date" name="from_date" value="<?php echo htmlentities($from_date); ?>">
    <label for="to_date">To:</label>
    <input type="date" id="to_date" name="to_date" value="<?php echo htmlentities($to_date); ?>">
    <button type="submit">Filter</button>
</form>
<table>
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <?php
            $from_date = isset($_POST['from_date']) ? $_POST['from_date'] : '';
            $to_date = isset($_POST['to_date']) ? $_POST['to_date'] : '';
            $user_orders=[];
            // Fetch orders for the current user
            if (!empty($from_date) && !empty($to_date)) {
                $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? AND DATE(created_at) BETWEEN ? AND ? AND order_status != 'Pending' ");
                $stmt->execute([$user['customer_id'], $from_date, $to_date]);
                $user_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            ?>
            <tr>
            <td><?php echo $user['name']; ?><button class="toggle-customer-details" data-customer-id="<?php echo $user['customer_id']; ?>">+</button></td>
                <td>$<?php
                    $all_order_total = 0;
                    foreach ($user_orders as $order) {
                        $all_order_total += $order['total_amount'];
                    }
                    echo $all_order_total;
                ?></td>
            </tr>
            <tr class="user-orders" id="order-details-<?php echo $user['customer_id']; ?>" style="display: none;">
                <td colspan="2">
                    <!-- Hidden table containing order details -->
                    <table>
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Order Status</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_orders as $order): ?>
                                <tr>
                        
                                    <td><?php echo $order['created_at']; ?> <button class="toggle-details" data-order-id="<?php echo $order['order_id']; ?>">+</button></td>
                                    <td><?php echo $order['order_status']; ?></td>
                                    <td>$<?php echo $order['total_amount']; ?></td>
                                </tr>
                                <!-- Add the following row for displaying product details -->
      <tr class="order-product-details" id="details-<?php echo $order['order_id']; ?>">
         <td colspan="5">
            <div class="order-details">
               <!-- Product details will be populated here -->
            </div>
         </td>
      </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<script src="js/adminChecks.js"></script>
</body>
</html>
