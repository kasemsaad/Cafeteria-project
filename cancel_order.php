<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit();
}

// Check if the order ID is provided in the request
if (!isset($_GET['order_id'])) {
    // Redirect to an error page or display an error message
    echo "Order ID is not provided";
    exit();
}

// Get the order ID from the request
$order_id = $_GET['order_id'];

// Initialize PDO connection
$db = new db();
$conn = $db->get_connection();

// Check if the order belongs to the logged-in customer
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND customer_id = ?");
$stmt->execute([$order_id, $_SESSION['customer_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    // If the order does not belong to the logged-in customer, redirect to an error page or display an error message
    echo "Order not found or does not belong to the logged-in customer";
    exit();
}

// Check if the order status is already cancelled or not pending
if ($order['order_status'] !== 'In Progress') {
    // If the order status is not pending, redirect to an error page or display an error message
    echo "Order cannot be cancelled as it is not in In Progress status";
    exit();
}

// Update the order status to cancelled
// $stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ?");
$stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");

$stmt->execute([$order_id]);

// Redirect to a success page or return a success message
echo "Order cancelled successfully";

?>
