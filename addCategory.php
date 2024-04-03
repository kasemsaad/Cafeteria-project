<?php
require("db.php");
$db = new db();
$conn = $db->get_connection();

$errors = [];

if(isset($_POST['add_category'])){ 
  
    // Retrieve form data and validate
    $category_id = validate($_POST['category_id']);
    $category_name = validate($_POST['category_name']);
    $created_at = validate($_POST['created_at']); 
    $updated_at = validate($_POST['updated_at']);

    // Validate category ID and name
    if(strlen($category_id) < 1){
        $errors['category_id'] = "Category ID must be at least 1 number.";
    }
    if(strlen($category_name) < 2){
        $errors['category_name'] = "Category name must be at least 2 characters long.";
    }

    // Check for errors
    if(count($errors) > 0){
        // Display errors below the form
        $error_message = "There are errors in the form. Please correct them and try again.";
    } else {
        try {
            $db = new db();

            // Check if category name already exists
            $existing_category = $db->get_data("categories", "category_name = '$category_name'");
            if($existing_category->rowCount() > 0){
                $errors['category_name'] = "Category name already exists.";
                $error_message = "Category name already exists.";
            } else {
                // Insert category into database
                $db->insert_data("categories", "category_id, category_name, created_at, updated_at", "'$category_id', '$category_name', '$created_at', '$updated_at'");
                // Redirect to success page
                header("Location:viewAllCategory.php");
                exit();
            }
        } catch(PDOException $e) {
            echo $e->getMessage();
            // Redirect to error page
            header("Location: addCategory.php?error=1");
            exit();
        }
    }
}

function validate($data) {
    $data = trim($data); 
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
        .navbar a {
        text-decoration: none;
    }
    .container{margin-top:100px}
body{
    background-image: url("images/channels4_profile.jpg");
}
</style>
    <title>Add Category</title>
</head>

<body>
<?php
    // Fetch customer name and image using customer_id
    $stmt = $conn->prepare("SELECT name, profile_image FROM customers WHERE customer_id = ?");
    $stmt->execute([$_COOKIE['customer_id']]);
    $login_user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="navbar" style="background-color: #333; color: white; display: flex; justify-content: space-between; align-items: center; height: 56px;">
    <div class="navbar-left" style="margin-left:10px;">
    <a style="color: white;" href="Orders_checks.php">Home |</a>
        <a style="color: white;" href="viewAllUsers.php">Users |</a>
        <a style="color: white;" href="userMakeOrder.php">Manual Order |</a>
        <a style="color: white;" href="adminChecks.php">Checks |</a>
        <a style="color: white;" href="viewAllCategory.php">Categories |</a>
        <a style="color: white;" href="addCategory.php">Add Category |</a>
        <a style="color: white;" href="viewAllProduct.php">Products |</a>
        <a style="color: white;" href="AddProduct.php">Add Product</a>
    </div>
    <div class="navbar-right">
        <div class="user-info" style="display: flex; align-items: center;">
            <img src="images/<?php echo $login_user['profile_image']; ?>" alt="User Photo" style="width: 40px; height: 40px; border-radius: 50%; margin-right:10px;">
            <span><?php echo $login_user['name'] ; ?></span>
            <a style="color: orange;margin-left:10px;" href="index.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </div>
</div>
    <div class="container">
        <?php if(isset($error_message)): ?>
        <div class="row justify-content-center">
            <div class="alert alert-danger col-md-6" role="alert">
                <?php echo $error_message; ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Add Category
                    </div>
                    <div class="card-body">
                        <form action="addCategory.php" method="POST">
                            <div class="form-group">
                                <label for="category_id">Category ID</label>
                                <input type="text" class="form-control" id="category_id" name="category_id" required>
                            </div><br>
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <?php if(isset($errors['category_name'])): ?>
                                <div class="text-danger"><?php echo $errors['category_name']; ?></div>
                                <?php endif; ?>
                                <input type="text" class="form-control" id="category_name" name="category_name"
                                    required>
                            </div><br>
                            <!-- <div class="form-group">
                                <label for="created_at">Created At (YYYY-MM-DD HH:MM:SS)</label>
                                <input type="text" class="form-control" id="created_at" name="created_at"
                                    placeholder="2024-03-18 08:30:00">
                            </div><br>
                            <div class="form-group">
                                <label for="updated_at">Updated At (YYYY-MM-DD HH:MM:SS)</label>
                                <input type="text" class="form-control" id="updated_at" name="updated_at"
                                    placeholder="2024-03-18 08:30:00">
                            </div><br> -->
                            <button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
                        </form>
                    </div>

                </div>