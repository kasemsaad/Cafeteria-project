<?php
session_start();
include 'connection.php';

// Initialize PDO connection
$db = new db();
$conn = $db->get_connection();

// Fetch pending orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_status = 'In Progress'");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       body {
    background-image: url("images/channels4_profile.jpg");
}

     /* CSS styles for user container and form */
     .user-container {
    position: fixed;
    top: 20px;
    left: 20px !important;
    display: flex;
    align-items: center;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

        .date-filter-form {
           
            top: 60px;
           
            display: flex;
            align-items: center;
        }

        .date-filter-form label {
            margin-right: 5px;
        }
        .container{
            margin-top:150px;
        }
    </style>
</head>
<body>


<?php
    // Fetch customer name and image using customer_id
    $stmt = $conn->prepare("SELECT name, profile_image FROM customers WHERE customer_id = ?");
<<<<<<< HEAD
    $stmt->execute([$_COOKIE['customer_id']]);
=======
    $stmt->execute([$_SESSION['customer_id']]);
>>>>>>> 0234e42f838691d478a9dceb2fe8b919a0983908
    $login_user = $stmt->fetch(PDO::FETCH_ASSOC);

    
  ?>

<div class="navbar">
   <div class="navbar-left">
      <a href="Orders_checks.php">Home</a>
      <a href="#">Products</a>
      <a href="#">Users</a>
      <a href=" userMakeOrder.php">Manual Order</a>
      <a href="adminChecks.php">Chicks</a>
      
   </div>
   <div class="row height d-flex justify-content-center align-items-center">
     <div class="col-md-6">
       </div>
   </div>
   <div class="navbar-right">
      <div class="user-info">
      <img src="images/<?php echo $login_user['profile_image']; ?>" alt="User Photo" style=" width: 40px;
    height: 40px;
    border-radius: 50%;">
         <span><?php echo $login_user['name']; ?></span>
         <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>

      </div>
   </div>
</div>

  

<!-- Form to specify date range -->
<div>

</div>
<div class="container">
    <?php foreach ($orders as $order): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Orders</h1>
                <form method="post" class="date-filter-form">
    <label for="from_date">From:</label>
    <input type="date" id="from_date" name="from_date" value="<?php echo htmlentities($from_date); ?>">
    <label for="to_date">To:</label>
    <input type="date" id="to_date" name="to_date" value="<?php echo htmlentities($to_date); ?>">
    <button type="submit">Filter</button>
</form>
                <table class="table table-bordered text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th colspan="5">Order Details</th>
                    </tr>
                    <tr>
                        <th>Order Date</th>
                        <th>Customer Name</th>
                        <th>Room Number</th>
                        <th>Ext</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $order['created_at']; ?></td>
                        <td><?php echo getCustomerName($conn, $order['customer_id']); ?></td>
                        <td><?php echo $order['room_number']; ?></td>
                        <td><?php echo getExt($conn, $order['customer_id']); ?></td>
                        <td>
                            <button type="button" class="btn btn-primary deliver-btn" data-order-id="<?php echo $order['order_id']; ?>">Deliver</button>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5">Order Items</th>
                    </tr>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Image</th>
                    </tr>
                    <?php foreach (getOrderItems($conn, $order['order_id']) as $item): ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo $item['price']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><img src="images/<?php echo $item['image']; ?>" alt="Product Image" style="max-width: 100px;"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/Orders_checks.js"></script>

</body>
</html>

<?php
function getCustomerName($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT name FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    return $customer['name'];
}

function getExt($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT ext FROM rooms WHERE room_no = (SELECT room_no FROM customers WHERE customer_id = ?)");
    $stmt->execute([$customer_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    return $room['ext'];
}

function getOrderItems($conn, $order_id) {
    $stmt = $conn->prepare("SELECT products.product_name, products.price, order_details.quantity, products.image
                            FROM order_details
                            INNER JOIN products ON order_details.product_id = products.product_id
                            WHERE order_details.order_id = ?");
    $stmt->execute([$order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>