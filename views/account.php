<?php

require_once __DIR__ . "/../models/user.php";
require_once __DIR__ . "/../models/vehicle_details.php";
require_once __DIR__ . "/../models/vehicle.php";
require_once __DIR__ . "/../models/own.php";

$userModel = new User();
$vehicleModel = new Vehicle();
$vehicleDetailsModel = new VehicleDetails();
$ownModel = new Own();

$userID = $userModel->getUserCookie();
$response = $userModel->isAdmin($userID);
$dbVehicles = $vehicleDetailsModel->getVehicleDetails();

if (isset($userID)) {
  if (!$userModel->checkUserSession($userID)) {
    echo "<script> alert ('Please Login First');
    window.location = '../index.php'</script>";
  }
} else {
  echo "<script> alert ('Please Login First');
  window.location = '../index.php'</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (isset($_POST['logout'])){
    $userModel->logout($userID);
    header("Location: ../index.php");
  };
}

if ($response["success"]) {
  if ($response["data"][0]["admin"]) {
    header("Location: accountAdmin.php");
  }
}

$response = $ownModel->getVehiclesByUser($userID);
if ($response['success']) {
  $vehicleByUser = $response['data'];
} else {
  $vehicleByUser = array();
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

        <div class="col-md-9">

          <div class="row ml-2">

            <?php for ($i = 0; $i < count($vehicleByUser); $i++) { ?>
              <div class="card mr-1" style="width: 15rem;">
                <div class="card-body">
                  <h5 class="card-title text-capitalize"><?php echo $vehicleByUser[$i]["type"]; ?></h5>
                  <h6 class="card-subtitle mb-2 text-muted text-capitalize">
                    <?php echo $vehicleByUser[$i]["manufacturer"] . ' ' . $vehicleByUser[$i]["model"]; ?>
                  </h6>
                  <p class="card-text mb-0"> <strong>Year: </strong> <?php echo $vehicleByUser[$i]["year"]; ?></p>
                  <p class="card-text mb-0 text-capitalize"> <strong>Engine: </strong> <?php echo $vehicleByUser[$i]["engine"]; ?></p>
                  <p class="card-text"> <strong>Licence: </strong> <?php echo $vehicleByUser[$i]["licence_details"]; ?></p>
                  <div class="float-right">
                    <button onClick="createBooking(<?php echo $vehicleByUser[$i]["vehicle_id"] ?>)" class="btn btn-outline-info my-2 my-sm-0 ml-2" type="button" data-toggle="modal" data-target="#booking-modal">Create Booking</button>
                  </div>
                </div>
              </div>
            <?php } ?>

            <button class="card text-center btn-outline-success" data-toggle="modal" data-target="#addvehicle-modal"" style=" width: 15rem;">
              <div class="card-body">
                <h3>ADD VEHICLE</h3>
              </div>
            </button>

          </div>
        </div>
      </div>
    </div>

    <!-----------------------------------------------ADD VEHICLE MODAL---------------------------------------------->

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
                <label for="type">Type</label>
                <select class="form-control" id="selectType">
                  <option>Chose a type</option>
                  <option value="car">Car</option>
                  <option value="motorbike">Motorbike</option>
                  <option value="small van">Small Van</option>
                  <option value="small bus">Small bus</option>
                </select>
              </div>

              <div class="form-group">
                <label for="manufacturer">Manufacturer</label>
                <select class="form-control" id="selectManufacturer">
                  <option>Chose a manufacturer</option>
                </select>
              </div>

              <div class="form-group">
                <label for="model">Model</label>
                <select class="form-control" id="selectModel">
                  <option>Chose a model</option>
                </select>
              </div>

              <div class="form-group">
                <label for="year">Year</label>
                <select name="vehicle_details_id" class="form-control" id="selectYear">
                  <option>Chose a year</option>
                </select>
              </div>

              <div class="form-group">
                <label for="type">Engine</label>
                <select class="form-control" name="selectEngine" id="selectEngine">
                  <option>Chose an engine</option>
                  <option value="diesel">Diesel</option>
                  <option value="petrol">Petrol</option>
                  <option value="hybrid">Hybrid</option>
                  <option value="electric">Electric</option>
                </select>
              </div>

              <div class="form-group">
                <input type="text" name="licence" id="licence" class="form-control" placeholder="Licence Number" required>
              </div>


            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-----------------------------------------------BOOKING MODAL---------------------------------------------->

    <div class="modal fade" role="dialog" id="booking-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Create Booking</h3>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form id="booking-form">
            <input type="hidden" id="vehID" name="vehID"/>
            <div class="modal-body">

              <div class="form-group">
                <label for="type">Booking Type</label>
                <select class="form-control" id="selectBookingType" name="selectType">
                  <option>Chose a type</option>
                  <option value="anual service">Annual Service (min €200)</option>
                  <option value="major service">Major Service (min €500)</option>
                  <option value="major repair">Major Repair (min €500)</option>
                  <option value="other repair">Other Repair</option>
                </select>
              </div>

              <div class="form-group">
                <label for="type">Description</label>
                <textarea type="text" name="details" id="details" class="form-control" placeholder="Describe the repair/fault" required></textarea>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class='col-sm-6'>
                    <div class="form-group">
                      <div class='input-group date' id='datetimepicker3'>
                        <input type='text' name="date" class="form-control" placeholder="Date" id="dateInput" disabled/>
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

              

            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">BOOK</button>
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
  <script src="../static/js/account_controller.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</body>

</html>