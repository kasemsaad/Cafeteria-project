<?php  
// if (!isset($_COOKIE['email'])) {
//     header("location:index.php");
//   }
$id = $_GET['id'];

require 'connection.php';
$Name = $_POST["Name"];
$Email = $_POST["Email"];
$Room = $_POST["Room"];
$Ext = $_POST["Ext"];
$From = $_FILES['CustomerImage']['tmp_name'];
$Img = $_FILES['CustomerImage']['name'];
move_uploaded_file($From, "./images/" . $Img);

$err = [];

if (strlen($Name) < 2) {
    $err['Name'] = " Name is Not Valid";
}

if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    $err['Email'] = " is not valid";
}

if (!is_numeric($Room)) {
    $err['Room'] = " is not number ";
}
if (!is_numeric($Ext) ) {
    $err["Ext"] = "Ext must be Number Room";
}

if (count($err) > 0) {
    header("location:editUser.php?err=" . urlencode(json_encode($err)) . "&id=$id");

} else {

    try {


$id=$_GET['id'];
$db=new db();
$upd=" name='$Name',email='$Email',room_no='$Room' ,ext='$Ext', profile_image='$Img' ";
$res=$db->update_data("customers",$upd,"customer_id=$id");
header("location:viewAllUsers.php?success"); 
} catch (PDOException $e) {
    header("location:editUser.php?id=$id?err=". $e->getMessage()); 

    // die ("Connection failed: " . $e->getMessage());
}

}

?>
