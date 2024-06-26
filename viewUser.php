<?php
require "connection.php";
if (!isset($_COOKIE['Email'])) {
    header("location:index.php");
} elseif ($_COOKIE["role"] !== "Admin") {
    header("location:index.php"); ////////// home
}
$id = $_GET['id'];
$db = new db();
$data = $db->get_dataone("customers", "customer_id=$id");
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <title>User</title>
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
</style>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3>User</h3>
                        <a class="btn btn-primary" href="viewAllUsers.php">Back</a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Room</th>
                                    <th>Ext</th>
                                    <th>picture</th>
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
                                            <?php echo $row['room_no']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['ext']; ?>
                                        </td>
                                        <td>
                                            <img src='./images/<?php echo $row['profile_image']; ?>' width='100'>
                                        </td>
                                        <td>
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