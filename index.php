<?php

require_once __DIR__ . "/models/user.php";
$userModel = new User();

$userID = $userModel->getUserCookie();

if (isset($userID)) {
  if ($userModel->checkUserSession($userID)) {
    header("Location: views/home.php");
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Index</title>
  <link rel="stylesheet" href="static/css/bootstrap.css">
  <link rel="stylesheet" href="static/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
</head>

<body style="background-color: rgb(220,53,69);">
  <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
    <a class="navbar-brand" href="index.php"><img src="img/logo.png" alt="Logo" width="100" height="70"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav m-auto">



        <li class="nav-item active">
          <a class="nav-link" href="#">HOME<span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">SERVICES</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">ABOUT US</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">CONTACT</a>
        </li>

      </ul>

      <form id="login" class="form-inline my-2 my-lg-0" method="POST">
        <input class="form-control mr-sm-2" type="text" name="email" placeholder="Email">
        <input class="form-control mr-sm-2" type="password" name="password" placeholder="Password">
        <input type="hidden" name="login" value="1">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Login</button>
        <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="button" data-toggle="modal" data-target="#signup-modal">Sign Up</button>
      </form>
    </div>
  </nav>

  <div class="modal fade" role="dialog" id="signup-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Sign Up</h3>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <form id="signup-form">
          <div class="modal-body">

            <div class="form-group">
              <input type="text" name="fname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="form-group">
              <input type="text" name="surname" class="form-control" placeholder="Surname" required>
            </div>
            <div class="form-group">
              <input type="text" name="address" class="form-control" placeholder="Address" required>
            </div>
            <div class="form-group">
              <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
            </div>
            <div class="form-group">
              <input type="email" name="sp_email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
              <input type="password" name="sp_password" class="form-control" placeholder="Password" required>
            </div>

          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Sign Up</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  </nav>



  <main role="main" class="main-custom main-jumbotron">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron mb-0" style="border-radius:0;">
      <div class="container">
        <h1 class="display-3">Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a
          jumbotron and three supporting pieces of content. Use it as a starting point to create something more
          unique.
        </p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more »</a></p>
      </div>
    </div>

    <div class="container-fluid p-3 mb-2 bg-danger text-white">
      <!-- Example row of columns -->
      <div class="row">

        <div class="col-md">
        </div>
        <div class="col-md">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris
            condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.
            Donec sed odio dui. </p>
          <p><a class="btn btn-secondary" href="#" role="button">View details »</a></p>
        </div>
        <div class="col-md">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris
            condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.
            Donec sed odio dui. </p>
          <p><a class="btn btn-secondary" href="#" role="button">View details »</a></p>
        </div>
        <div class="col-md">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula
            porta
            felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut
            fermentum
            massa justo sit amet risus.</p>
          <p><a class="btn btn-secondary" href="#" role="button">View details »</a></p>
        </div>
        <div class="col-md">
        </div>
      </div>
    </div>

  </main>

  <footer class="footer">
    <div>
      <p>© Company 2017-2020</p>
    </div>
  </footer>

  <script src="static/js/jquery-3.5.1.min.js"></script>
  <script src="static/js/bootstrap.js"></script>
  <script src="static/js/index_controller.js"></script>
  


</body>

</html>