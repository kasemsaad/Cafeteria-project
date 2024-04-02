<?php
session_start();
include 'connection.php'; // Include your database connection file

// Get username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Validate the username and password (you should use proper validation and secure password hashing)
// For demonstration purposes, let's assume the username and password are 'admin'
if ($username === 'admin' && $password === 'admin') {
    // If credentials are valid, set the user as logged in
    $_SESSION['customer_id'] = 1; // Set the customer ID (you should replace this with the actual customer ID from the database)
    header('location: userMakeOrder.php'); // Redirect to the desired page after successful login
    exit();
} else {
    // If credentials are invalid, redirect back to the login page with an error message
    $_SESSION['login_error'] = 'Invalid username or password';
    header('location: index.php');
    exit();
}
?>
