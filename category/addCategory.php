<?php
require("db.php");

if(isset($_POST['add_category'])){ 
  
    $category_id = validate($_POST['category_id']);
    $category_name = validate($_POST['category_name']);
    $created_at = validate($_POST['created_at']); 
    $updated_at = validate($_POST['updated_at']);

  
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
        header("Location: viewAllCategory.php");
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
    <title>Add Category</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                Add Category
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
                <form action="addCategory.php" method="POST">
                  <div class="form-group">
                    <label for="category_id">Category ID</label>
                    <input type="text" class="form-control" id="category_id" name="category_id" required>
                  </div><br>
                  <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                  </div><br>
                  <div class="form-group">
                    <label for="created_at">Created At (YYYY-MM-DD HH:MM:SS)</label>
                    <input type="text" class="form-control" id="created_at" name="created_at" placeholder="2024-03-18 08:30:00" >
                  </div><br>
                  <div class="form-group">
                    <label for="updated_at">Updated At (YYYY-MM-DD HH:MM:SS)</label>
                    <input type="text" class="form-control" id="updated_at" name="updated_at" placeholder="2024-03-18 08:30:00">
                  </div><br>
                  <button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

