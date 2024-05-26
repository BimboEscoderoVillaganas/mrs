<?php
include_once('../config.php'); // Including database configuration
$db = new Database(); // Creating database instance

$tid = $_SESSION['tourID']; // Assuming user ID is stored in session
// Fetch logged-in user's username
$sql = "SELECT tour_un FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['tour_un'];
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
            $sql = "INSERT INTO approved (r_id, user_id, b_id, r_dstntn, r_date, r_hr, r_ampm, date_approved) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $params = [
                $reservation['r_id'],
                $reservation['user_id'],
                $reservation['b_id'],
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

// Fetch reservation count
$sql = "SELECT COUNT(*) AS reservation_count FROM reservation WHERE status IS NULL OR status NOT IN ('approved', 'declined')";
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

    <!-- Pagination -->
    <link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css">

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
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img src="../img/logo.jpg" height="50" width="50"> &nbsp;
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <li><a href="#" style="font-family: Times New Roman; font-size: 30px;">Motorcycle Reservation</a></li>
            </ul>

            <ul class="nav navbar-nav" style="font-family: Times New Roman;">
                <li>
                    <a href="index.php">Motorcycles</a>
                </li>
                <li class="active">
                    <a href="reservation.php">Reservation
                        <span class="notification"><?php echo $reservation_count; ?></span></a>
                </li>
                <li>
                    <a href="approved.php">Approved</a>
                </li>
                <li>
                    <a href="declined.php">Declined</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right" style="font-family: Times New Roman;">
                <li>
                    <?php echo '<span style="margin-right: 10px;">Logged in as: ' . htmlspecialchars($loggedInUser) . '</span>'; ?>
                    <?php include_once('../includes/logout.php'); ?>
                </li>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
<!-- Navbar -->

<br>
<br>
<br>
<br>

<div class="container">
    <br>
    <br>
    <br>
    <table id="myTable" class="table table-striped">
        <thead>
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
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM reservation r INNER JOIN motors b ON b.b_id = r.b_id
                        INNER JOIN users t ON t.user_id = r.user_id WHERE r.status IS NULL OR r.status NOT IN ('approved', 'declined')";
                $res = $db->getRows($sql);

                foreach ($res as $r) {
                    $r_id = $r['r_id'];
                    $tfn = $r['user_fN'];
                    $tmn = $r['user_mN'];
                    $tln = $r['user_lN'];
                    $tcontact = $r['tour_contact'];
                    $taddress = $r['user_address'];
                    $img = $r['b_img'];
                    $bn = $r['b_name'];
                    $bon = $r['b_on'];
                    $dstntn = $r['r_dstntn'];
                    $bprice = $r['b_price'];
                    $rdate = $r['r_date'];
                    $r_hr = $r['r_hr']; // Fix variable name here
                    $rampm = $r['r_ampm'];

                    $oras = $r_hr . ' ' . $rampm; // Fix variable name here
            ?>
            <tr>
                <td class="align-img"><?php echo $tfn . ' ' . $tmn . ' ' . $tln; ?></td>
                <td class="align-img"><?php echo $tcontact; ?></td>
                <td class="align-img"><?php echo $taddress; ?></td>
                <td class="align-img"><center><img src="<?php echo $img; ?>" width="50" height="50"></center></td>
                <td class="align-img"><?php echo $bn; ?></td>
                <td class="align-img"><?php echo $bon; ?></td>
                <td class="align-img"><?php echo $dstntn; ?></td>
                <td class="align-img"><?php echo $rdate; ?></td>
                <td class="align-img"><?php echo $oras; ?></td>
                <td class="align-img"><?php echo 'Php ' . number_format($bprice, 2); ?></td>
                <td class="align-img">
                    <a class="btn btn-success btn-xs" href="reservation.php?approveid=<?php echo $r_id; ?>">
                        Approve
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </a>
                    <a class="btn btn-danger btn-xs" href="reservation.php?declineid=<?php echo $r_id; ?>">
                        Decline
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </a>
                </td>
            </tr>
            <?php
                }//end foreach loop of display all reservation
            ?>
        </tbody>
    </table>
</div>

<!-- main content -->

</body>
<script src="../bootstrap/js/jquery-1.11.1.min.js"></script>
<script src="../bootstrap/js/dataTables.js"></script>
<script src="../bootstrap/js/dataTables2.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<!--pagination-->
<link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css"><!--search box positioning-->
<script src="../bootstrap/js/jquery.dataTables2.js"></script>

<script>
//script for pagination
$(document).ready(function(){
    $('#myTable').dataTable();
});
</script>

</html>

<?php
$db->Disconnect();
?>
