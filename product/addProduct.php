
<?php

if(isset($_POST['add_category'])){ 
    // Receive data from the form
    $category_id = validate($_POST['category_id']);
    $category_name = validate($_POST['category_name']);
    $created_at = validate($_POST['created_at']); 
    $updated_at = validate($_POST['updated_at']);

    // Validate data
    $errors = [];
    if(strlen($category_id) < 1){
        $errors['category_id'] = "Category ID must be at least 2 characters long.";
    }
    if(strlen($category_name) < 2){
        $errors['category_name'] = "Category name must be at least 2 characters long.";
    }

    if(count($errors) > 0){
        // If there are errors, redirect the user back to the form with error messages
        header("Location: addCategory.php?errors=" . json_encode($errors));
        exit();
    }

    try{
        // Add the category using the insert_data function
        $db = new db();
        $db->insert_data("categories", "category_id, category_name, created_at, updated_at", "'$category_id', '$category_name', '$created_at', '$updated_at'");
        header("Location: listCategory.php");
        exit(); // Ensure that the script stops execution after redirection
    }catch(PDOException $e){
        echo $e->getMessage();
        // If an error occurs, redirect the user to an error page
        header("Location: addCategory.php?error=1");
        exit(); // Ensure that the script stops execution after redirection
    }
}

function validate($data){
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Add Product</title>
    </head>
<body>
        <div class="container mt-5">
            <div class="row justify-content-center">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                   <h3>Add Product </h3> 
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
                    <form action="addProduct.php" method="POST">
                        <div class="form-group">
                            <label for="product_id">Product ID</label>
                            <input type="number" class="form-control" id="product_id" name="product_id" required>
                          </div><br>
                          <div class="form-group">
                            <label for="category_name">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                          </div><br>
                          <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price"pattern="^\d+(\.\d{1,2})?$" required placeholder="3.5" >
                          </div><br>
                          <div class="form-group">
                            <input class="form-control btn btn-primary" accept="image/*" required type="file" name="product_img">
                            <br></br>
        
          <select class="form-control" name="categories">
          <option value="Select category">Select Category Name:</option>
          <option value="USA">Hot Drinks</option>
          <option value="Canada">Canada</option>
          <option value="UK">UK</option>
          <option value="Australi">Australia</option>
          <option value="Germany">Germany</option>
          </select>
        <!-- Add more options here -->
                          </div><br>
                          <button type="submit" class="btn btn-primary" value="Save" name="add_product">Save</button>
                          <button type="submit" class="btn btn-danger" value="Reset" name="add_product">Reset</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        </body> 
</form>
</script>
</body>
</html>


