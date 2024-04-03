<?php
require("db.php");
$db = new db(); 

$id = $_GET['id'];
$data = $db->get_data("categories", "category_id=$id");

if (!$data || $data->rowCount() == 0) {
    echo "Category not found.";
    exit;
}
$category = $data->fetch(PDO::FETCH_ASSOC);
//header("location:viewAllCategory.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit Category</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Edit Category
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
                        <form action="updateCategory.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $category['category_id']; ?>">
                            <div class="form-group">
                                <label for="category_id">Category ID</label>
                                <input type="text" class="form-control" id="category_id" name="category_id"
                                    value="<?php echo $category['category_id']; ?>">
                            </div><br>
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control" id="category_name" name="category_name"
                                    value="<?php echo $category['category_name']; ?>" required>
                            </div><br>
                            <div class="form-group">
                                <label for="created_at">Created At (YYYY-MM-DD HH:MM:SS)</label>
                                <input type="text" class="form-control" id="created_at" name="created_at"
                                    value="<?php echo $category['created_at']; ?>" required>
                            </div><br>
                            <div class="form-group">
                                <label for="updated_at">Updated At (YYYY-MM-DD HH:MM:SS)</label>
                                <input type="text" class="form-control" id="updated_at" name="updated_at"
                                    value="<?php echo $category['updated_at']; ?>" required>
                            </div><br>
                            <button type="submit" class="btn btn-primary" name="update_category">Update
                                Category</button>
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