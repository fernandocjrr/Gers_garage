<?php

require_once __DIR__ . "/../models/user.php";
$userModel = new User();

$userID = $userModel->getUserCookie();

if (isset($userID)) {
  if (!$userModel->checkUserSession($userID)) {
    echo "<script> alert ('Please Login First');
    window.location = '../index.php'</script>";
  }
} else {
  echo "<script> alert ('Please Login First');
  window.location = '../index.php'</script>";
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=Bootstree, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Boostrap</title>
  <link rel="stylesheet" href="../static/css/bootstrap.css">
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
    <a class="navbar-brand" href="../index.php"><img src="../img/logo.png" alt="Logo" width="100" height="70"></a>
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

      <a href="account.php" class="btn btn-outline-success my-2 my-sm-0"> <span class="glyphicon glyphicon-user"></span>User Account</a>
      <span class="glyphicon glyphicon-user"></span>
      <a class="btn btn-outline-success my-2 my-sm-0" href="account.php">Account</a>

    </div>
  </nav>



  < <main role="main">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
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
          <button type="button" class="btn btn-default" aria-label="Left Align">
            <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>
          </button>

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

      <hr>

    </div> <!-- /container -->

    </main>

    <footer class="container">
      <p>© Company 2017-2020</p>
    </footer>

    <script src="../static/js/jquery-3.5.1.min.js"></script>
    <script src="../static/js/bootstrap.bundle.min.js"></script>
    <script src="../static/js/bootstrap.js"></script>
    <script src="../static/js/index_controller.js"></script>


</body>

</html>