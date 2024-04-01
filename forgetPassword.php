<?php require 'connection.php';

$err = [];
if (isset($_GET['err'])) {
  $err = json_decode($_GET['err'], true);
}

?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  <title>Forget Password</title>
</head>
<style>
  body {
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    background-image: url("./images/19266-Main.jpg");
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
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Forget Password </h3>
            <form method="post" action="sendEmail.php">

              <div class="row">

                <div class="col-md-6 mb-4">
                  <div class="form-outline">
                    <input type="email" required name="Email" placeholder="Email"
                      class="form-control form-control-lg" />
                    <label class="form-label" for="email">Email</label>


                  </div>
                </div>


              </div>


              <div class="mt-4 pt-2">
      
                <input class="btn btn-primary btn-lg" type="submit" value="Submit" name="forgetpassword" />
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