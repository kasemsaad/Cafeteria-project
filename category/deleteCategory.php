<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require("db.php");
$db = new db(); 

$id=$_GET['id'];
$db->delete_data("categories", "category_id=$id");
header("location:viewAllCategory.php");

?>