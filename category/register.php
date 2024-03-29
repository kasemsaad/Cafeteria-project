<?php 

$errors=[];
if(isset($_GET['errors'])){
    $errors =json_decode($_GET['errors'],true);
    foreach($errors as $error){
        echo $error."<br>";
    }
}

?>
    <form action="./categoryController.php" method="post" enctype="multipart/form-data">
       <label for="fname">First Name:</label>
       <input type="text" id="fname" required name="fname"><br>
       <?php 
       if(isset($errors['fname'])){
        echo $errors['fname'];
       }
       ?><br>
       <label for="lname">Last Name:</label>
       <input type="text" id="lname" required name="lname"><br>
       <?php 
       if(isset($errors['lname'])){
        echo $errors['lname'];
       }
       ?><br>
       Email<input type="text" name="email" required placeholder="email"><br>
       <?php 
       if(isset($errors['email'])){
        echo $errors['email'];
       }
       ?><br>
       Pass<input type="number" name="pass" required placeholder="pass"><br>
       <?php 
       if(isset($errors['pass'])){
        echo $errors['pass'];
       }
       ?><br>
      <input type="submit" value="Register" name="register">
      <input type="reset">

    </form>
