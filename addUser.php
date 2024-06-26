<?php
require 'connection.php';
if (!isset($_COOKIE['Email'])) {
  header("location:index.php");
} elseif ($_COOKIE["role"] !== "Admin") {
  header("location:index.php"); ////////// userMakeOrder
}
$err = [];
if (isset($_GET['err'])) {
  $err = json_decode($_GET['err'], true);
}
$db = new db();
$conn = $db->get_connection();

$data = $db->get_data("rooms");
$dataExt = $db->ext();
?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  <title>Add Users</title>
</head>
<style>
  body {
    /* background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    background-image: url("./images/19266-Main.jpg"); */
   
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
<section class=" gradient-custom">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Add User Cafetria</h3>
            <form action="CustomerControler.php" method="post" enctype="multipart/form-data">

              <div class="row">

                <div class="col-md-6 mb-4">
                  <div class="form-outline">
                    <input type="text" id="Name" class="form-control form-control-lg" name="Name" />
                    <label class="form-label" for="Name">Name</label>

                    <?php
                    if (isset($err['Name'])) {
                      echo "<span style='color:red'>$err[Name]</span> <br>";
                    }
                    ?>
                  </div>
                </div>

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="email" id="emailAddress" class="form-control form-control-lg" name="Email" />
                    <label class="form-label" for="email">Email</label>
                    <?php

                    if (isset($err['Email'])) {
                      echo "<span style='color:red'>$err[Email]</span> <br>";
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <select name="Room" class="form-control form-control-lg">
                      <option value="">Select Room</option>
                      <?php foreach ($data as $row): ?>
                        <option value="<?php echo $row['room_no']; ?>">
                          <?php echo $row['room_no']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <label class="form-label" for="Room">Room*</label>
                  </div>
                </div>

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <select name="Ext" class="form-control form-control-lg">
                      <option value="">Select Ext</option>
                      <?php foreach ($dataExt as $row): ?>
                        <option value="<?php echo $row['ext']; ?>">
                          <?php echo $row['ext']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <label class="form-label" for="Ext">Ext*</label>
                  </div>
                </div>

              </div>


              <div class="row">

                <div class="col-md-6 mb-4 d-flex align-items-center">
                  <div class="form-outline datepicker w-100">
                    <input type="password" class="form-control form-control-lg" id="Password" name="Password" />
                    <label for="password" class="form-label">Password</label>
                    <?php
                    if (isset($err['Password'])) {
                      echo "<span style='color:red'>$err[Password]</span> <br>";
                    }
                    ?>
                  </div>
                </div>



                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="password" id="Confirm Password" class="form-control form-control-lg"
                      name="ConPassword" />
                    <label class="form-label" for="conpassword">Confirm Password</label>
                    <?php
                    if (isset($err['ConPassword'])) {
                      echo "<span style='color:red'>$err[ConPassword]</span> <br>";
                    }
                    ?>
                  </div>
                </div>

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="file" id="CustomerImage" class="form-control form-control-lg" name="CustomerImage" />
                    <label class="form-label" for="profileImage">Profile Image</label>
                  </div>
                </div>


              </div>
              <div class="mt-4 pt-2">

                <input class="btn btn-primary btn-lg" type="submit" value="Submit" name="addUser" />
                <input class="btn btn-primary btn-lg" type="reset" value="Reset" name="Reset" />
                <a class="btn btn-primary btn-lg" href="viewAllUsers.php">Back</a>


              </div>
          </div>

        </div>

        </form>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>
