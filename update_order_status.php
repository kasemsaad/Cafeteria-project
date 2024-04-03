<?php
// Include the database connection file
include 'connection.php';

// Check if the order ID is received via POST
if(isset($_POST['order_id'])) {
    // Get the order ID from the POST data
    $order_id = $_POST['order_id'];
 // Get the database connection
 $db = new db();
 $conn = $db->get_connection();
    // Update the order status in the database
    try {
        // Prepare the SQL statement to update the order status
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'Completed' WHERE order_id = :order_id");
        
        // Bind the parameter
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();

        // Return the updated status as a response
        echo 'Completed';
    } catch(PDOException $e) {
        // Handle any errors that occur during the database operation
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // If the order ID is not received, return an error message
    echo 'Error: Order ID not received.';
}
?>
