<?php

require "connection.php";
if (isset($_POST['addUser'])) {
    $Name = validation($_POST["Name"]);
    $Email = $_POST["Email"];
    $Room = $_POST["Room"];
    $Ext = $_POST["Ext"];
    $Password = $_POST["Password"];
    $ConPassword = $_POST["ConPassword"];
    $role = "User";
    $From = $_FILES['CustomerImage']['tmp_name'];
    $Img = $_FILES['CustomerImage']['name'];
    move_uploaded_file($From, "./images/" . $Img);
    /////////////////////////////////////////////////////////
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
    if (!is_numeric($Ext)) {
        $err["Ext"] = "Ext must be Number Room";
    }
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/';

    if (!preg_match($pattern, $Password)) {
        $err["Password"] = "Password does not meet the required pattern. It must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long.";
        ;
    }

    if ($Password !== $ConPassword) {
        $err["ConPassword"] = "Passwords do not match";
    }
    if (count($err) > 0) {
        header("location:addUser.php?err=" . json_encode($err));
    } else {

        try {
            $DB = new db();
            $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

            $values = [$Name, $Email, $hashed_password, $Room, $Ext, $role, $Img]; // Removed extra comma after $Name
            $DB->insert_data("customers", "name, email, password, role, room_no, ext, profile_image", $values);
            header("location:viewAllUsers.php?success");
        } catch (PDOException $e) {
            header("location:viewAllUsers.php?err=" . $e->getMessage());
        }

    }
} else if (isset($_POST["login"])) {
    try {
        $Email = $_POST['Email'];
        $Password = $_POST['Password'];

        $db = new db();
        $res = $db->get_data("customers", "email = ?", array($Email));

        if (password_verify($Password, $res[0]['password'])) b2 {
            setcookie("customer_id", $res[0]['customer_id'], time() + (86400 * 30), "/"); 
            setcookie("Email", $Email, time() + (86400 * 30), "/"); // Example: sets a cookie named "Email" with the value of $Email
            setcookie("role", $res[0]['role'], time() + (86400 * 30), "/");
            if (isset($_COOKIE['role'])=="User") {
                header("location:userMakeOrder.php?success"); /////////////enter after check
            } else {
                # code...
                header("location:Orders_checks.php?success"); /////////////enter after check
            }
        } else {

            header("location:index.php?err=login");
        }
    } catch (mysqli_sql_exception $e) {
        header("location:CustomerControler.php?err=mysqli_sql_exception");
        exit();
    }
} else if (isset($_POST["resetPassword"])) {

    $Email = $_POST['Email'];
    $code = $_POST['code'];
    $Password = $_POST["Password"];
    $ConPassword = $_POST["ConPassword"];
    echo $Email, $code, $Password;
    $err = [];

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $err['Email'] = " is not valid";
    }
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/';
    if (!preg_match($pattern, $Password)) {
        $err["Password"] = "Password does not meet the required pattern. It must contain at least one lowercase letter, one uppercase letter, one digit, and be at least 8 characters long.";
        ;
    }
    if ($Password !== $ConPassword) {
        $err["ConPassword"] = "Passwords do not match";
    }
    if (count($err) > 0) {
        header("location:resetPassword.php?err=" . json_encode($err));
    } else {
        try {
            $db = new db();
            $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
            $upd = "password='$hashed_password'";
            $getdata = $db->get_data("customers", "email = ?", array($Email));
            $res = $db->update_data("customers", $upd, "email='$Email'");
            if ($getdata[0]['resetcode'] == $code) {
                header("location:index.php?reset_success"); /////////////enter after check
            } else {
                $err = ["err" => "invalid email or code"];
                header("location:resetPassword.php?err=" . json_encode($err));
            }

        } catch (mysqli_sql_exception $e) {
            header("location:resetPassword.php?err=mysqli_sql_exception");
            exit();
        }
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