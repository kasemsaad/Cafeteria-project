<?php
if (!isset($_COOKIE['Email'])) {
  header("location:index.php");
} elseif ($_COOKIE["role"] !== "Admin") {
  header("location:home.php"); ////////// home
}
require 'connection.php';
$id = $_GET['id'];
$db = new db();
$res = $db->delete_data("customers", "customer_id=$id");
header("location:viewAllUsers.php");
?>