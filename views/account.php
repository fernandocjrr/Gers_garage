<?php

require_once __DIR__ . "/../models/user.php";
$userModel = new User();

$userID = $userModel->getUserCookie();
$response = $userModel->isAdmin($userID);

if (isset($userID)) {
  if (!$userModel->checkUserSession($userID)) {
    echo "<script> alert ('Please Login First');
    window.location = 'index.php'</script>";
  }
} else {
  echo "<script> alert ('Please Login First');
  window.location = 'index.php'</script>";
}

if ($response["success"]) {
  if ($response["data"][0]["admin"]) {
    header("Location: accountAdmin.php");
  }
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
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">

        <li class="nav-item active">
          <a class="nav-link" href="#">Home<span class="sr-only">(current)</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>

        <li class="nav-item">
          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
      </ul>
      <a class="btn btn-outline-success my-2 my-sm-0" href="account.php"><i class="fa fa-user-o">Account</i></a>

    </div>
  </nav>
  < <main role="main">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="button" data-toggle="modal" data-target="#addvehicle-modal">Add Vehicle</button>
      </div>
    </div>


    <div class="modal fade" role="dialog" id="addvehicle-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Add Vehicle</h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form id="addvehicle-form">
            <div class="modal-body">
              <div class="form-group">
                <div class="form-group">
                  <div class="btn-group">
                    
                  <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Type
  </button>
                    <div class="dropdown-menu dropdown-menu-left">
                      <button class="dropdown-item" type="button">Motorbike</button>
                      <button class="dropdown-item" type="button">Car</button>
                      <button class="dropdown-item" type="button">Small Van</button>
                      <button class="dropdown-item" type="button">Small Bus</button>
                    </div>
                  </div>

                </div>
                <div class="form-group">
                  
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Manufacturer
                    </button>
                    <div class="dropdown-menu dropdown-menu-left">
                      <button class="dropdown-item" type="button">Action</button>
                      <button class="dropdown-item" type="button">Another action</button>
                      <button class="dropdown-item" type="button">Something else here</button>
                    </div>
                  </div>
                
                </div>
                <div class="form-group">
                  
                <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Model
                    </button>
                    <div class="dropdown-menu dropdown-menu-left">
                      <button class="dropdown-item" type="button">Action</button>
                      <button class="dropdown-item" type="button">Another action</button>
                      <button class="dropdown-item" type="button">Something else here</button>
                    </div>
                  </div>

                  <div class="btn-group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Year
                    </button>
                    <div class="dropdown-menu dropdown-menu-left">
                      <button class="dropdown-item" type="button">Action</button>
                      <button class="dropdown-item" type="button">Another action</button>
                      <button class="dropdown-item" type="button">Something else here</button>
                    </div>
                  </div>

                </div>

              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>







    <div class="container">
      <!-- Example row of columns -->
      <div class="row">

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