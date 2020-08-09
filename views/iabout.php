<?php

require_once __DIR__ . "/../models/user.php";
$userModel = new User();

$userID = $userModel->getUserCookie();

if (isset($userID)) {
  if ($userModel->checkUserSession($userID)) {
    header("Location: about.php");
  }
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Home</title>
  <link rel="stylesheet" href="../static/css/bootstrap.css">
  <link rel="stylesheet" href="../static/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
</head>

<body>
  <nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
    <a class="navbar-brand" href="../index.php"><img src="../img/logo.png" alt="Logo" width="100" height="70"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav m-auto">



        <li class="nav-item">
          <a class="nav-link" href="../index.php">HOME<span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item  active">
          <a class="nav-link" href="iabout.php">ABOUT US</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="icontact.php">CONTACT</a>
        </li>

      </ul>

    </div>
  </nav>



  <main role="main" class="main-custom main-jumbotron">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron mb-0" style="border-radius:0;">
      <div class="container">

        <h1 class="display-3" style="color: white;"><b>ABOUT US</b></h1>
        <p>.<br><br><br><br><br><br>
        </p>
      </div>
    </div>

    <div class="container-fluid p-3 mb-2 bg-danger text-white">
      <!-- Example row of columns -->
      <div class="row">
      <div class="col-md">
</div>
        <div class="col-md">
          <p>We are a well established family business, with an excellent reputation for quality service at affordable prices.Our success is founded upon a total commitment to customer satisfaction where you can be assured that your interests are considered above all. We are an all makes cars and vans garage services business where you can expect to achieve up to 40% saving on franchised dealer prices. We’ll do the work to the same standards using the latest technology, and our highly experienced and skilled technicians will be happy to provide feedback and facilitate the inspection of replaced parts when requested, We serve local business and private customers with a fully comprehensive garage service and offer a collect and delivery service within a 5 mile radius where required.</p>
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
  <script src="../static/js/jquery-3.5.1.min.js"></script>
  <script src="../static/js/bootstrap.js"></script>
  <script src="../static/js/index_controller.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">


</body>

</html>