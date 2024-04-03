<?php
require "connection.php";
// if (!isset($_SESSION['Email'])) {
//     header("location:index.php");

// } elseif ($_COOKIE["role"] !== "Admin") {
//     header("location:index.php"); ////////// home
// }
$db = new db();
$conn = $db->get_connection();
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
<style>
body{
    background-image: url("images/channels4_profile.jpg");
}


    .card-registration .select-input.form-control[readonly]:not([disabled]) {
        font-size: 1rem;
        line-height: 2.15;
        padding-left: .75em;
        padding-right: .75em;

    }

    .card-registration .select-arrow {
        top: 13px;
    }
    .navbar a {
        text-decoration: none;
    }
</style>

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
                                            <a class="btn btn-primary"
                                                href="viewUser.php?id=<?php echo $row['customer_id']; ?>">view</a>
                                            <a class="btn btn-info"
                                                href="editUser.php?id=<?php echo $row['customer_id']; ?>">edit</a>
                                            <a class="btn btn-danger"
                                                href="delete.php?id=<?php echo $row['customer_id']; ?>">Delete</a>

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