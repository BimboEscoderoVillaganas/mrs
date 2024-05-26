<?php 
session_start();
include_once('../config.php'); // Including database configuration
$db = new Database(); // Creating database instance

$tid = $_SESSION['tourID']; // Assuming user ID is stored in session
// Fetch logged-in user's username
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['user_name'];

// Handling approval process
if (isset($_GET['approveid'])) {
    $approve_id = $_GET['approveid'];

    try {
        // Start transaction
        $db->Begin();

        // Get the reservation details
        $sql = "SELECT * FROM reservation WHERE r_id = ?";
        $reservation = $db->getRow($sql, [$approve_id]);

        if ($reservation) {
            // Insert into approved table
            $sql = "INSERT INTO approved (r_id, user_id, motor_id, r_dstntn, r_date, r_hr, r_ampm, date_approved) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $params = [
                $reservation['r_id'],
                $reservation['user_id'],
                $reservation['motor_id'],
                $reservation['r_dstntn'],
                $reservation['r_date'],
                $reservation['r_hr'],
                $reservation['r_ampm']
            ];
            $db->insertRow($sql, $params);

            // Update status in reservation table
            $sql = "UPDATE reservation SET status = 'approved' WHERE r_id = ?";
            $db->updateRow($sql, [$approve_id]);

            // Commit transaction
            $db->Commit();
            echo "<script>alert('Approval successful!');</script>";
        } else {
            // Rollback transaction
            $db->RollBack();
            echo "<script>alert('Invalid reservation ID!');</script>";
        }
    } catch (Exception $e) {
        // Rollback transaction
        $db->RollBack();
        echo "<script>alert('Approval failed! Error: " . $e->getMessage() . "');</script>";
    }
}

// Handling decline process
if (isset($_GET['declineid'])) {
    $decline_id = $_GET['declineid'];

    try {
        // Start transaction
        $db->Begin();

        // Update status in reservation table
        $sql = "UPDATE reservation SET status = 'declined' WHERE r_id = ?";
        $db->updateRow($sql, [$decline_id]);

        // Commit transaction
        $db->Commit();
        echo "<script>alert('Decline successful!');</script>";
    } catch (Exception $e) {
        // Rollback transaction
        $db->RollBack();
        echo "<script>alert('Decline failed! Error: " . $e->getMessage() . "');</script>";
    }
}

// Fetch reservation count excluding 'approved', 'declined', and 'deleted' statuses
$sql = "SELECT COUNT(*) AS reservation_count FROM reservation WHERE status IS NULL OR status NOT IN ('approved', 'declined', 'deleted')";
$result = $db->getRow($sql);
$reservation_count = $result['reservation_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MRS MANAGER</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.dataTables.css">
    <link rel="stylesheet" href="notif.css">
    <style type="text/css">
        .navbar { margin-bottom:0px !important; }
        .carousel-caption { margin-top:0px !important }

        td.align-img {
            line-height: 3 !important;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="../img/logo.jpg" height="50" width="50"> &nbsp;
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li><a href="#" style="font-family: Times New Roman; font-size: 30px;">Motorcycle Reservation</a></li>
            </ul>
            <ul class="nav navbar-nav" style="font-family: Times New Roman;">
                <li><a href="index.php">Motorcycles</a></li>
                <li class="active"><a href="reservation.php">Reservation <span class="notification"><?php echo $reservation_count; ?></span></a></li>
                <li><a href="approved.php">Approved</a></li>
                <li><a href="declined.php">Declined</a></li>
                            <li>
                                <a href="users.php">Users</a>
                            </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="font-family: Times New Roman;">
                <li>
                    <?php echo '<span style="margin-right: 10px;">Logged in as: ' . htmlspecialchars($loggedInUser) . '</span>'; ?>
                    <?php include_once('../includes/logout.php'); ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br /><br /><br /><br />

<div class="container">
    <br /><br /><br />
    <table id="myTable" class="table table-striped">
        <thead>
            <tr>
                <th>NAME</th>
                <th>CONTACT</th>
                <th>ADDRESS</th>
                <th><center>IMAGE</center></th>
                <th>BRAND NAME</th>
                <th>MODEL</th>
                <th>DESTINATION</th>
                <th>DATE</th>
                <th>TIME</th>
                <th>PRICE</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT r.*, b.b_name, b.b_model, b.b_price, b.b_img, t.user_fN, t.user_mN, t.user_lN, t.tour_contact, t.user_address
                        FROM reservation r
                        INNER JOIN motors b ON b.motor_id = r.motor_id
                        INNER JOIN users t ON t.user_id = r.user_id
                        WHERE r.status IS NULL OR r.status NOT IN ('approved', 'declined', 'deleted')";
                $reservations = $db->getRows($sql);

                foreach ($reservations as $reservation) {
                    $r_id = $reservation['r_id'];
                    $full_name = $reservation['user_fN'] . ' ' . $reservation['user_mN'] . ' ' . $reservation['user_lN'];
                    $contact = $reservation['tour_contact'];
                    $address = $reservation['user_address'];
                    $image = $reservation['b_img'];
                    $brand_name = $reservation['b_name'];
                    $model = $reservation['b_model'];
                    $destination = $reservation['r_dstntn'];
                    $date = $reservation['r_date'];
                    $time = $reservation['r_hr'] . ' ' . $reservation['r_ampm'];
                    $price = 'Php ' . number_format($reservation['b_price'], 2);
            ?>
            <tr>
                <td class="align-img"><?php echo htmlspecialchars($full_name); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($contact); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($address); ?></td>
                <td class="align-img"><center><img src="<?php echo htmlspecialchars($image); ?>" width="50" height="50"></center></td>
                <td class="align-img"><?php echo htmlspecialchars($brand_name); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($model); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($destination); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($date); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($time); ?></td>
                <td class="align-img"><?php echo htmlspecialchars($price); ?></td>
                <td class="align-img">
                    <a class="btn btn-success btn-xs" href="reservation.php?approveid=<?php echo htmlspecialchars($r_id); ?>">
                        Approve
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </a>
                    <a class="btn btn-danger btn-xs" href="reservation.php?declineid=<?php echo htmlspecialchars($r_id); ?>">
                        Decline
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- main content -->

</body>
<script src="../bootstrap/js/jquery-1.11.1.min.js"></script>
<script src="../bootstrap/js/dataTables.js"></script>
<script src="../bootstrap/js/dataTables2.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script>
$(document).ready(function(){
    $('#myTable').dataTable();
});
</script>
</html>

<?php
$db->Disconnect();
?>
