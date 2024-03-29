<form action="categoryController.php" method="post" >
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
      <input type="submit" value="Login" name="login">
      <input type="reset">
    
</form>
<?php 
    if(isset($_GET['error'])){
        echo "email or pass is error";
    }
 
?>