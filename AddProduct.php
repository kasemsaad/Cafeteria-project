<?php
require("db.php");

$errors = [];
$error_message = "";
$db = new db();
$conn = $db->get_connection();

if(isset($_POST['add_product'])){
   
    if(isset($_POST['product_id']) && isset($_POST['product_name']) && isset($_POST['description']) && isset($_POST['status']) && isset($_POST['price']) && isset($_POST['category_id']) && isset($_FILES['image'])) {
        
        $product_id = validate($_POST['product_id']);
        $product_name = validate($_POST['product_name']);
        $description = validate($_POST['description']);
        $status = validate($_POST['status']);
        $price = validate($_POST['price']);
        $category_id = validate($_POST['category_id']);
        $image = $_FILES['image'];

        //------------ Validate product ID and name ---------------------//
        if(strlen($product_id) < 1){
            $errors['product_id'] = "Product ID must be at least 1 number.";
        }
        if(strlen($product_name) < 2){
            $errors['product_name'] = "Product name must be at least 2 characters long.";
        }

        if(count($errors) > 0){
            $error_message = "There are errors in the form. Please correct them and try again.";
        } else {
            try {
                $db = new db();

                //------ -- Check if product name already exists ---------------//
                $existing_product = $db->get_data("products", "product_name = '$product_name'");
                if($existing_product->rowCount() > 0){
                    $error_message = "Product name already exists.";
                } else {
        
                    $target_dir = "productImages/";
                    $target_file = $target_dir . basename($_FILES["image"]["name"]);
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                 //--------- Insert product into database ----------//
                        $db->insert_data("products", "product_name, description, status, price, image, category_id", "'$product_name', '$description', '$status', '$price', '$target_file', '$category_id'");
                        header("Location: viewAllProduct.php");
                        exit();
                    } else {
                        $error_message = "Error uploading image.";
                    }
                }
            } catch(PDOException $e) {
                echo $e->getMessage();
                $error_message = "An error occurred while adding the product.";
            }
        }
    } else {
        $error_message = "Missing form fields.";
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
    body{
    background-image: url("images/channels4_profile.jpg");
}
</style>
    <title>Add Product</title>
</head>
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
<div class="container mt-8">
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
                    Add Product
                </div>
                <div class="card-body">
                    <form action="AddProduct.php" method="post" class="form-control" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product_id">Product ID</label>
                            <input type="number" class="form-control" id="product_id" name="product_id" required>
                        </div><br>
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div><br>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div><br>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div><br>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div><br>
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div><br>
                        <div class="form-group">
                            <label for="category_id">Category ID</label>
                            <input type="number" class="form-control" id="category_id" name="category_id" required>
                            <a href="addCategory.php">Add Category</a>
                        </div><br>
                        <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
                    </form>
                   
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                crossorigin="anonymous"></script>
            </body>

</html>