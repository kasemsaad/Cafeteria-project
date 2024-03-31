<?php
require "connection.php";
// if (!isset($_COOKIE['email'])) {
//     header("location:index.php");
//   }
$err = [];
if (isset($_GET['err'])) {
  $err = json_decode($_GET['err'], true);
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
  <title>Add Users</title>
</head>
<style>
  .gradient-custom {
    background: #f093fb;
    background: -webkit-linear-gradient(to bottom right, rgba(240, 147, 251, 1), rgba(245, 87, 108, 1));
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
<section class=" gradient-custom">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Edit User Cafetria</h3>
            <form action="update.php?id=<?php echo $id ?>" method="post" enctype="multipart/form-data">
              <?php foreach ($data as $use): ?>
                <div class="row">

                  <div class="col-md-6 mb-4">
                    <div class="form-outline">
                      <input type="text" id="Name" class="form-control form-control-lg" name="Name" value=<?php echo $use['name'] ?> />
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
                      <input type="email" id="emailAddress" class="form-control form-control-lg" name="Email" value=<?php echo $use['email'] ?> />
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
                      <input type="tel" id="Room" name="Room" class="form-control form-control-lg" name="Room" value=<?php echo $use['room_no'] ?> />
                      <label class="form-label" for="Room">Room</label>
                      <?php
                      if (isset($err['Room'])) {
                        echo "<span style='color:red'>$err[Room]</span> <br>";
                      }
                      ?>
                    </div>
                  </div>

                  <div class="col-md-6 mb-4 pb-2">
                    <div class="form-outline">
                      <input type="tel" id="Ext" class="form-control form-control-lg" name="Ext" value=<?php echo $use['ext'] ?> />
                      <label class="form-label" for="Ext">Ext</label>
                      <?php
                      if (isset($err['Ext'])) {
                        echo "<span style='color:red'>$err[Ext]</span> <br>";
                      } ?>
                    </div>
                  </div>


                  <div class="col-md-6 mb-4 pb-2">
                    <div class="form-outline">
                      <input type="file" id="CustomerImage" class="form-control form-control-lg" name="CustomerImage"
                        value=<?php echo $use['profile_image'] ?> />
                      <label class="form-label" for="profileImage">Profile Image</label>
                    </div>
                  </div>

                </div>
            </div>

          </div>
          <div class="mt-4 pt-2">
          <?php endforeach; ?>
          <input class="btn btn-primary btn-lg" type="submit" value="save" />
          <input class="btn btn-primary btn-lg" type="reset" value="Reset" name="Reset" />
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