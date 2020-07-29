<?php

require_once __DIR__ . "/../models/user.php";
require_once __DIR__ . "/../models/booking.php";

$userModel = new User();
$bookingModel = new Booking();

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
    if (!$response["data"][0]["admin"]) {
        header("Location: account.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
        $startDate = date("Y-m-d", strtotime($_GET['startDate']));
        $endDate = date("Y-m-d", strtotime($_GET['endDate']));
    } else {
        header("Location: accountAdmin.php");
    }

    $roster = $bookingModel->getBookingByInterval($startDate, $endDate);
 
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../static/css/invoice.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=Bootstrap, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Roster</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="2">
                    ROSTER
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>DATE</td>
                <td>STAFF</td>
            </tr>
            <?php for ($i = 0; $i < count($roster["data"]); $i++) { ?>
                <tr>
                    <td><?php echo $roster["data"][$i]["date"]; ?></td>
                    <td><?php echo $roster["data"][$i]["staff_fname"]; ?></td>
                <tr>
                
            <?php } ?>
            

        </tbody>
    </table>


    <button onClick="window.print()">PRINT</button>

</body>

</html>