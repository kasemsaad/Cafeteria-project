<?php 
require("db.php");
$db = new db();
$data = $db->get_data("categories");
$result = $data->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>List Category</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                       <h3>All Categories</h3> 
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($result as $row): ?>
                                    <tr>
                                        <td><?php echo $row['category_id']; ?></td>
                                        <td><?php echo $row['category_name']; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td><?php echo $row['updated_at']; ?></td>
                                        <td>
                                            <form action="editCategory.php" method="GET" style="display: inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['category_id']; ?>">
                                                <button type="submit" class="btn btn-primary">Edit</button>
                                            </form>
                                            <form action="deleteCategory.php" method="GET" style="display: inline;">
        <input type="hidden" name="id" value="<?php echo $row['category_id']; ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
    </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php //echo "<a href=\"http://localhost/PHPnewproject/category/deleteCategory.php?id={$row['category_id']}\">delete</a>" ; 
?>