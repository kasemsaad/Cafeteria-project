<!-- <?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// echo  $_GET['fname'];
$err = [];
if (isset($_GET['err'])) {
  $err = json_decode($_GET['err'], true);
}
?>



  <?php
  if (isset($err['fname'])) {
    echo "<span style='color:red'>$err[fname]</span> <br>";
  }
  ?>

  Last name <input type="text" name="lname" placeholder="Last name"><br>
  <?php
  if (isset($err['lname'])) {
    echo "<span style='color:red'>$err[lname]</span> <br>";
  }
  ?>
  Address <textarea name="address"> </textarea> <br>
  <?php
  if (isset($err['address'])) {
    echo "<span style='color:red'>$err[address]</span> <br>";
  }
  ?>

  <?php
  if (isset($err['email'])) {
    echo "<span style='color:red'>$err[email]</span> <br>";
  }
  ?>
  Password <input type="password" name="password" placeholder="Password"><br>
  <?php
  if (isset($err['password'])) {
    echo "<span style='color:red'>$err[password]</span> <br>";
  }
  ?> -->
<?php


require 'connection.php';




?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
</head>
<style>
  .gradient-custom {
    /* fallback for old browsers */
    background: #f093fb;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to bottom right, rgba(240, 147, 251, 1), rgba(245, 87, 108, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to bottom right, rgba(240, 147, 251, 1), rgba(245, 87, 108, 1))
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
<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row justify-content-center align-items-center h-100">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registration Form</h3>
            <form action="CustomerControler.php" method="post"  enctype="multipart/form-data">

              <div class="row">

                <div class="col-md-6 mb-4">
                  <div class="form-outline">
                    <input type="text" id="firstName" class="form-control form-control-lg" name="FirstName" />
                    <label class="form-label" for="firstName">First Name</label>
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <div class="form-outline">
                    <input type="text" id="lastName" class="form-control form-control-lg" name="LastName" />
                    <label class="form-label" for="lastName">Last Name</label>
                  </div>
                </div>

              </div>


              <div class="row">

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="email" id="emailAddress" class="form-control form-control-lg" name="Email" />
                    <label class="form-label" for="email">Email</label>
                  </div>
                </div>


                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="tel" id="phoneNumber" name="Address" class="form-control form-control-lg"
                      name="Address" />
                    <label class="form-label" for="phoneNumber">Address</label>
                  </div>
                </div>

              </div>


              <div class="row">

                <div class="col-md-6 mb-4 d-flex align-items-center">
                  <div class="form-outline datepicker w-100">
                    <input type="password" class="form-control form-control-lg" id="Password" name="Password" />
                    <label for="password" class="form-label">Password</label>
                  </div>
                </div>

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="tel" id="phoneNumber" class="form-control form-control-lg" name="Phone" />
                    <label class="form-label" for="phoneNumber">Phone Number</label>
                  </div>
                </div>

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="password" id="Confirm Password" class="form-control form-control-lg"
                      name="ConPassword" />
                    <label class="form-label" for="phoneNumber">Confirm Password</label>
                  </div>
                </div>

                <div class="col-md-6 mb-4 pb-2">
                  <div class="form-outline">
                    <input type="file" id="profileImage" class="form-control form-control-lg" name="ProfileImage" />
                    <label class="form-label" for="profileImage">Profile Image</label>
                  </div>
                </div>


              </div>
          </div>

        </div>
        <div class="mt-4 pt-2">

          <input class="btn btn-primary btn-lg" type="submit" value="Submit" />
        </div>

        </form>
      </div>
    </div>
  </div>
  </div>
  </div>
</section>