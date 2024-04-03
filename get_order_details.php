<?php
include 'connection.php'; // Include the file where you define the db class

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Get the database connection
    $db = new db();
    $conn = $db->get_connection();

    try {
        // Prepare and execute the SQL query
        $stmt = $conn->prepare("SELECT products.product_name, products.description, products.price, products.image, order_details.quantity 
                                FROM order_details 
                                JOIN products ON order_details.product_id = products.product_id 
                                WHERE order_details.order_id = ?");
        $stmt->execute([$orderId]);

        // Fetch all the results
        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output order details as JSON
        header('Content-Type: application/json'); // Set the response header to indicate JSON content
        echo json_encode($orderDetails); // Output JSON data
    } catch (PDOException $e) {
        // Handle any errors that occur during execution
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
