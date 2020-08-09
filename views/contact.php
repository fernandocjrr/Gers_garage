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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['logout'])) {
    $userModel->logout($userID);
    header("Location: ../index.php");
  };
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
          <a class="nav-link" href="home.php">HOME<span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item ">
          <a class="nav-link" href="about.php">ABOUT US</a>
        </li>

        <li class="nav-item active">
          <a class="nav-link" href="contact.php">CONTACT</a>
        </li>

      </ul>

      <a class="btn btn-outline-success my-2 my-sm-0" href="account.php"><i class="fa fa-user-circle-o "> Account</i></a>
      <form method="POST" action="">
        <input type="hidden" value="1" name="logout"><button class="btn btn-outline-info ml-1 my-2 my-sm-0" type="submit"> Logout </button></form>

    </div>
  </nav>



  <main role="main" class="main-custom main-jumbotron">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron mb-0" style="border-radius:0;">
      <div class="container">

        <h1 class="display-3" style="color: white;"><b>CONTACT</b></h1>
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
          <p><b>Call:</b> <i>123456</i><br>
          <b>Visit</b>: <i>99 Address, Dublin, Ireland</i><br>
          <b>Email:</b> <i>customerservice@gergarage.ie</i></p>
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