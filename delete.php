<?php  
// if (!isset($_COOKIE['email'])) {
//     header("location:index.php");
//   }
require 'connection.php';
$id=$_GET['id'];
$db=new db();
$res=$db->delete_data("customers","customer_id=$id");
header("location:viewAllUsers.php");
?>
