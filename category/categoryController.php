<?php 
require("db.php");
$db=new db();
/*
if(isset($_POST['login'])){

    try{
        $result=$db->get_data("categories","email='{$_POST['email']}' and pass='{$_POST['pass']}'");
       /* $stm=$connection->prepare("select * from categories where email=? and pass=?");
        $stm->execute([$_POST['email'],$_POST['pass']]);
        $data=$result->fetch(PDO::FETCH_ASSOC);

        if($data){
            session_start();
            $_SESSION['fname']=$data['fname'];
            $_SESSION['lname']=$data['lname'];
            $_SESSION['email']=$data['email'];
            /*setcookie("fname",$data['fname'],time()+60*60*24*7*12);
            setcookie("lname",$data['lname'],time()+60*60*24*7*12);   
            setcookie("email",$data['email'],time()+60*60*24*7*12);
            header("Location:listCategory.php");
        }else{
            header("Location:login.php?error=1");
        }
    }catch(PDOExceptoin $e){
        echo $e->getMessage();
    }
   //var_dump($_POST);
}
else if(isset($_POST['register'])){
$fname = validate($_POST['fname']);
$lname = validate($_POST['lname']);
$email = validate($_POST['email']);
$pass = validate($_POST['pass']);
//$address = validate($_POST['address']);
$errors =[];
if(strlen($fname)<2){
    $errors['fname']="First name must more than 2 character";
    //header("Location:lab1.html");
}
if(strlen($lname)<2){
    $errors['fname']="Last name must more than 2 character";
    
}
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors['email']="Not Valid Email";
    
}
/*if(strlen($address)<10){
    $errors['address']="Address must more than 10 character";
    
}*/
/*if($_FILES['emp_img']['size']>10000000){
    $errors['img_size']="img size must be between 5 and 50 ";
}
if(count($errors)>0){
    //json_decode($errors);
    header("Location:register.php?errors=".json_encode($errors));
}else{
    $from=$_FILES['emp_img']['tmp_name'];
    $to=$_FILES['emp_img']["name"];
    move_uploaded_file($from,"./img/$to".$img);}

    try{
    
    $stm=$connection->prepare("insert into customers(category_id,category_name,created_at,updated_at) values(?,?,?,?,?)");
    $stm->execute([$fname,$lname,$email,$pass,$to]);
    header("Location:view.php");
}catch(PDOExceptoin $e){
    echo $e->getMessage();
    header("Location:registerphp");
}
}
function validate($data){
    $data = trim($data); 
    $data = addslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}*/


?>
