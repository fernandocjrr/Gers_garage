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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['logout'])) {
    $userModel->logout($userID);
    header("Location: ../index.php");
  };
}

if ($response["success"]) {
  if (!$response["data"][0]["admin"]) {
    header("Location: account.php");
  }
}
$response = $userModel->getUserInfo($userID);
if ($response['success']) {
  $UserInfo = $response['data'][0];
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=Bootstreep, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Boostrap</title>
  <link rel="stylesheet" href="../static/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
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
      <a class="btn btn-success my-2 my-sm-0" href="#"><i class="fa fa-user-circle-o "> Account</i></a>
      <form method="POST" action="">
        <input type="hidden" value="1" name="logout"><button class="btn btn-outline-info ml-1 my-2 my-sm-0" type="submit"> Logout </button></form>
    </div>
  </nav>

  <!-----------------------------------------------SIDE BAR---------------------------------------------->

  <main role="main" class="pt-5">

    <div class="container-fluid pt-5">
      <div class="row pl-2">
        <div class="col-md-3 bg-light">
          <div class="row">
            <p><strong>User Name: </strong> <?php echo $UserInfo["first_name"] . ' ' . $UserInfo["surname"]; ?></p>
          </div>
          <div class="row">
            <p><strong>Phone: </strong> <?php echo $UserInfo["phone"]; ?></p>
          </div>
          <div class="row">
            <p><strong>Address: </strong> <?php echo $UserInfo["address"]; ?></p>
          </div>
          <div class="row">
            <p><strong>Email: </strong> <?php echo $UserInfo["email"]; ?></p>
          </div>
        </div>

        <!-----------------------------------------------VEHICLE CARDS---------------------------------------------->

        <button class="btn btn-outline-info my-2 my-sm-0 ml-2" type="button" data-toggle="modal" data-target="#viewbookings-modal">View Bookings</button>

        <!-----------------------------------------------BOOKING VIEW MODAL---------------------------------------------->

        <div class="modal fade" role="dialog" id="viewbookings-modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Booking Viewer</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <form id="viewbooking-form">
                <div class="modal-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="byday-tab" data-toggle="tab" href="#byday" role="tab">By Day</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="byweek-tab" data-toggle="tab" href="#byweek" role="tab">By Week</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="byday" role="tabpanel">

                      <div class="form-group mt-2">
                        <div class="row">
                          <div class='col-sm-6'>
                            <div class="form-group">
                              <div class='input-group date' id='datetimepicker3'>
                                <input type='text' class="form-control" placeholder="Date" id="dateInput" />
                                <div class="input-group-append">
                                  <span class="input-group-text">
                                    <span class="fa fa-calendar"></span>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="bookingbyday">
                      
                        <div class="card">
                          <div class="card-body">
                            This is some text within a card body.
                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="byweek" role="tabpanel">

                    <div class="form-group mt-2">
                        <div class="row">
                          <div class='col-sm-6'>
                            <div class="form-group">
                              <div class='input-group date' id='datetimepicker4'>
                                <input type='text' class="form-control" placeholder="Date" id="weekInput" />
                                <div class="input-group-append">
                                  <span class="input-group-text">
                                    <span class="fa fa-calendar"></span>
                                  </span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="bookingbyweek">
                      
                        <div class="card">
                          <div class="card-body">
                            This is some text within a card body.
                          </div>

                        </div>
                      </div>

                    </div>
                  </div>
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

  <footer class="container text-center">
    <p>© Company 2017-2020</p>
  </footer>




  <script src="../static/js/jquery-3.5.1.min.js"></script>
  <script src="../static/js/moment.js"></script>
  <script src="../static/js/bootstrap.bundle.js"></script>
  <script src="../static/js/bootstrap.js"></script>
  <script src="../static/js/bootstrap.min.js"></script>
  <script src="../static/js/accountadmin_controller.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</body>

</html>