<?php
require "connection.php";
// if (!isset($_COOKIE['email'])) {
//     header("location:index.php");
//   }
$db = new db();
// $data = $db->get_data("customers");
$data = $db->get_dataone("customers", " role='User' ");

?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <title>All Users</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3>All Users</h3>
                        <a class="btn btn-primary" href="addUser.php">add User</a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Date & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['customer_id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['email']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['updated_at']; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="viewUser.php?id=<?php echo $row['customer_id'];?>">view</a>
                                            <a class="btn btn-info" href="editUser.php?id=<?php echo $row['customer_id'];?>">edit</a>
                                            <a class="btn btn-danger" href="delete.php?id=<?php echo $row['customer_id']; ?>">Delete</a>

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

