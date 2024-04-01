<?php
require("db.php");
$db = new db(); 

if(isset($_POST['id'])) { 
    $id = $_POST['id'];
} 
else
 {
    echo "No ID provided.";
    exit;
 }


$id = $_GET['id'];
$data = $db->get_data("products", "product_id=$id");

if (!$data || $data->rowCount() == 0) {
    echo "Product not found.";
    exit;
}

$product = $data->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit Product</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Edit Product
                    </div>
                    <div class="card-body">
                        <!-- Display errors here if any -->
                        <?php if(isset($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($success_message)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success_message; ?>
                        </div>
                        <?php endif; ?>
                        <form action="updateProduct.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                            <div class="form-group">
                                <label for="product_id">Product ID</label>
                                <input type="text" class="form-control" id="product_id" name="product_id"
                                    value="<?php echo $product['product_id']; ?>">
                            </div><br>
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="<?php echo $product['product_name']; ?>" required>
                            </div><br>
                            <div class="form-control">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    pattern="^\d+(\.\d{1,2})?$" value="<?php echo $product['price']; ?>" required>
                            </div><br>
                            <div class="form-control">
                                <select class="form-control" name="status" id="status"
                                    value="<?php echo $product['status']; ?>" required>
                                    <option value="Select Statu">Select Status</option>
                                    <option value="available">available</option>
                                    <option value="unavailable">unavailable</option>
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    value="<?php echo $product['description']; ?>" required>
                            </div><br>
                            <div class="form-control">
                                <label for="category_id">category ID</label>
                                <input type="number" class="form-control" id="category_id" name="category_id"
                                    value="<?php echo $product['category_id']; ?>" required>
                            </div><br>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div><br>
                            <button type="submit" class="btn btn-primary" name="update_product">Update Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>