<?php
require("db.php");
$db = new db();

if(isset($_POST['id'])) { 
    $id = $_POST['id'];
} 
else
 {
    echo "No ID provided.";
    exit;
 }

$data = array(
    'product_id'=>$_POST['product_id'],
    'product_name' => $_POST['product_name'],
    'description' => $_POST['description'],
    'status' => $_POST['status'],
    'price' => $_POST['price'],
    'image' => $_POST['image'], 
    'category_id' => $_POST['category_id']
);

$condition = "product_id = $id"; 

try {
    $result = $db->update_data("products", $data, $condition);

    if ($result) {
        header("location:viewAllProduct.php");
        //echo "Category updated successfully.";
    } else {
        echo "Error updating Product.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
