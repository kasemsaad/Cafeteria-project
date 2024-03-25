<?php
require "connection.php";
echo "<h1>Hello</h1>";

$FirstName = validation($_POST["FirstName"]);
$LastName = validation($_POST["LastName"]);
$Email = $_POST["Email"];
$Room = $_POST["Room"];
$Phone = $_POST["Phone"];
$Password = $_POST["Password"];
$ConPassword = $_POST["ConPassword"];
$role = "User";
$From = $_FILES['CustomerImage']['tmp_name'];
$Img = $_FILES['CustomerImage']['name'];
move_uploaded_file($From, "./Img/" . $Img);
/////////////////////////////////////////////////////////
$err = [];

if (strlen($FirstName) < 2) {
    $err['FirstName'] = " Name is Not Valid";
}
if (strlen($LastName) < 2) {
    $err['LastName'] = " Name is Not Valid";
}

    
if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    $err['Email'] = " is not valid";
}

if (!is_numeric($Room)) {
    $err['Room'] = " is not number ";
}
if (!is_numeric($Phone) || strlen($Phone) != 11) {
    $err["Phone"] = "Phone number must be numeric and have 11 digits";
}
$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/';

    if(!preg_match($pattern,$Password)){
        $err["Password"] ="Password does not meet the required pattern. It must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long.";                ;
        }

if ($Password !== $ConPassword) {
    $err["ConPassword"] = "Passwords do not match";
}
if (count($err) > 0) {
    header("location:Register.php?err=" . json_encode($err));
} else {



    try {
        $DB = new db();
        $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

        $values = [$FirstName, $LastName, $Room, $Phone, $Password, $role, $Img];
        $DB->insert_data("customers", "first_name, last_name,email, room_no, Phone, password, role, profile_image", [$FirstName, $LastName, $Email, $Room, $Phone, $hashed_password, $role, $Img]);


    } catch (PDOException $e) {
        die ("Connection failed: " . $e->getMessage());
    }
}
function validation($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>