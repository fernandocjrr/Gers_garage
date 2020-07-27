<?php

require_once __DIR__ . "/../models/user.php";
require_once __DIR__ . "/../controllers/invoice.php";

$invoiceController = new Invoice();
$userModel = new User();

$total = 0;
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

    if (isset($_GET['bookingId'])) {
        $booking_id = intval($_GET['bookingId']);
    } else {
        header("Location: accountAdmin.php");
    }
}

$user_info = $invoiceController->getBookingById($booking_id);
$costs_info = $invoiceController->getCosts($booking_id);

if (isset($costs_info["success"])) {
    echo "<script> alert ('No cost assigned to this booking');
        window.location = 'accountAdmin.php'</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../static/css/invoice.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=Bootstrap, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>

<body>

    Customer: <?php echo $user_info['first_name'] . ' ' . $user_info['surname']; ?><br>
    Phone Number: <?php echo $user_info['phone']; ?><br>
    Address: <?php echo $user_info['address']; ?><br>
    Email: <?php echo $user_info['email']; ?><br>
    <br><br>
    Vehicle Type: <?php echo $user_info['type']; ?><br>
    Manufacturer: <?php echo $user_info['manufacturer']; ?><br>
    Model: <?php echo $user_info['model']; ?><br>
    Engine: <?php echo $user_info['engine']; ?><br>
    Year: <?php echo $user_info['year']; ?><br>
    Licence: <?php echo $user_info['licence_details']; ?><br>

    <br><br>
    <table>
        <thead>
            <tr>
                <th colspan="2">
                    SERVICES
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>SERVICE</td>
                <td>VALUE</td>
            </tr>
            <?php
            for ($i = 0; $i < count($costs_info); $i++) {
                if (!isset($costs_info[$i]["part_id"])) {
                   
            ?>
                    <tr>
                        <td><?php echo $user_info["fix_type"]; ?></td>
                        <td><?php echo $costs_info[$i]["cost"]; ?></td>
                    <tr>
                    <tr>
                        <td colspan="2">
                            <?php echo $costs_info[$i]["description"];?>
                        </td>
                    </tr>
                <?php
                }
            }
                ?>
            <thead>
                <tr>
                    <th colspan="2">
                        PARTS
                    </th>
                </tr>

                <td>PART</td>
                <td>VALUE</td>
            </thead>
            <tr>
            </tr>
            <?php
            for ($i = 0; $i < count($costs_info); $i++) {
                if (isset($costs_info[$i]["part_id"])) {
                    $sum_part = intval($costs_info[$i]["part_cost"]) * intval($costs_info[$i]["quantity"]);
                    $total += $sum_part;
            ?>
                    <tr>
                        <td><?php echo $costs_info[$i]["quantity"] . " x " . $costs_info[$i]["part"]; ?></td>
                        <td><?php echo $sum_part; ?></td>
                    <tr>
                <?php
                }else{
                    $total+=intval($costs_info[$i]["cost"]);
                }
            }
                ?>
            
        </tbody>
    </table>

    <br><br>

    <table>
        <tr>
            <td>TOTAL</td>
            <td><?php echo "€ ". $total;?></td>
        </tr>
        
    </table>

    <button onClick="window.print()">PRINT</button>

     </body> </html>