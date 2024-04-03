<?php 
require("db.php");

$db = new db();
$data = $db->get_data("products");
$result = $data->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>List Products</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3>All Products</h3>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Category Name</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($result as $row): ?>
                            <tr>
                                <td><?php echo $row['product_id']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                                <td><?php echo $row['category_id']; ?></td>
                                <td><img src="./productImages/<?php echo $row['image']; ?>" width="50" height="50"></td>
                                <td>
                                    <a class="btn btn-primary"
                                        href="viewAllProduct.php?id=<?php echo $row['product_id']; ?>">View</a>
                                    <a class="btn btn-warning"
                                        href="editProduct.php?id=<?php echo $row['product_id']; ?>">Edit</a>
                                    <a class="btn btn-danger"
                                        href="deleteProduct.php?id=<?php echo $row['product_id']; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>