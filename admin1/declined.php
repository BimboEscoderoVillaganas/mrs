<?php
include_once('../config.php'); //database
$db = new Database();

$tid = $_SESSION['tourID']; // Assuming user ID is stored in session// Fetch logged-in user's username
$sql = "SELECT tour_un FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['tour_un'];

// Fetch reservation count
$sql = "SELECT COUNT(*) AS reservation_count FROM reservation WHERE status IS NULL OR status NOT IN ('approved', 'declined')";
$result = $db->getRow($sql);
$reservation_count = $result['reservation_count'];
?>

<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MRS MANAGER</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.dataTables.css">
    <link rel="stylesheet" href="notif.css">

    <!--pagination-->
    <link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css"><!--search box positioning-->
    <script src="../bootstrap/js/jquery.dataTables2.js"></script>
</head>

<style type="text/css">
    .navbar {
        margin-bottom: 0px !important;
    }

    .carousel-caption {
        margin-top: 0px !important
    }

    td.align-img {
        line-height: 3 !important;
    }

    .notification {
        background-color: red;
        color: white;
        padding: 2px 5px;
        border-radius: 50%;
        position: relative;
        top: -10px;
        right: 10px;
    }
</style>

<body>

    <!-- begin whole content -->
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
                    <li>
                        <a href="reservation.php">Reservation
                            <span class="notification"><?php echo $reservation_count; ?></span></a>
                    </li>
                    <li>
                        <a href="approved.php">Approved</a>
                    </li>
                    <li class="active">
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
    <!-- end -->

    <br />
    <br />
    <br />
    <br />

    <!-- main content -->
    <div class="container">
        <br />
        <br />
        <table id="myTable" class="table table-striped">
            <thead>
                <th>NAME</th>
                <th>CONTACT</th>
                <th>ADDRESS</th>
                <th>
                    <center>IMAGE</center>
                </th>
                <th>BRAND NAME</th>
                <th>MODEL</th>
                <th>DESTINATION</th>
                <th>DATE</th>
                <th>TIME</th>
                <th>PRICE</th>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM reservation r
                                INNER JOIN motors b ON b.b_id = r.b_id
                                INNER JOIN users t ON t.user_id = r.user_id
                                WHERE r.status = 'declined'";
                $res = $db->getRows($sql);

                foreach ($res as $r) {
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
                    $rhr = $r['r_hr'];
                    $rampm = $r['r_ampm'];

                    $oras = $rhr . ' ' . $rampm;
                ?>
                    <tr>
                        <td class="align-img"><?php echo $tfn . ' ' . $tmn . ' ' . $tln; ?></td>
                        <td class="align-img"><?php echo $tcontact; ?></td>
                        <td class="align-img"><?php echo $taddress; ?></td>
                        <td class="align-img">
                            <center><img src="<?php echo $img; ?>" width="50" height="50"></center>
                        </td>
                        <td class="align-img"><?php echo $bn; ?></td>
                        <td class="align-img"><?php echo $bon; ?></td>
                        <td class="align-img"><?php echo $dstntn; ?></td>
                        <td class="align-img"><?php echo $rdate; ?></td>
                        <td class="align-img"><?php echo $oras; ?></td>
                        <td class="align-img"><?php echo 'Php ' . number_format($bprice, 2); ?></td>
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
<!--pagination-->
<link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css"><!--search box positioning-->
<script src="../bootstrap/js/jquery.dataTables2.js"></script>

<script>
    //script for pagination
    $(document).ready(function() {
        $('#myTable').dataTable();
    });
</script>

</html>

<?php
$db->Disconnect();
?>