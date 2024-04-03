<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if(isset($_POST['id'])) { 
    $id = $_POST['id'];
}  

else
 {
    echo "No ID provided.";
    exit;
 }


require("db.php");
$db = new db(); 

$id=$_GET['id'];
$db->delete_data("products", "product_id=$id");
header("location:viewAllProduct.php");

?>