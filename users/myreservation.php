<?php

include_once('../config.php'); //database
$db = new Database();
// Fetch approved reservation count
$tid = $_SESSION['tourID'];
$sql = "SELECT COUNT(*) AS approved_count FROM reservation WHERE user_id = ? AND status = 'approved'";
$result = $db->getRow($sql, [$tid]);
$approved_count = $result['approved_count'];

// Fetch declined reservation count
$tid = $_SESSION['tourID'];
$sql = "SELECT COUNT(*) AS declined_count FROM reservation WHERE user_id = ? AND status = 'declined'";
$result = $db->getRow($sql, [$tid]);
$declined_count = $result['declined_count'];

// Fetch logged-in user's username
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['user_name'];
?>

<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MRS USER</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.dataTables.css">

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
                    <li class="active">
                        <a href="myreservation.php">My Reservation</a>
                    </li>
                    <li>
                        <a href="approved.php">Approved Reservation
                            <span class="notification"><?php echo $approved_count; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="declined.php">Declined Reservation
                            <span class="notification"><?php echo $declined_count; ?></span>
                        </a>
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

		<?php
		// delete reserved
		if (isset($_GET['delr_id'])) {
			$delrid = $_GET['delr_id'];
			$tid = $_SESSION['tourID'];

			$sql = "DELETE FROM reservation WHERE user_id = ? AND r_id = ?";
			$res = $db->deleteRow($sql, [$tid, $delrid]);

			echo '
                                <div class="alert alert-success">
                                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                  <strong>Success!</strong> Cancel Reservation Successfully.
                                </div>
                            ';
			// header('Location: myreservation.php');
		}
		?>

		<br />
		<table id="myTable" class="table table-striped">
			<thead>
				<th>
					<center>IMAGE</center>
				</th>
				<th>BRAND NAME</th>
				<th>MODEL</th>
				<th>DESTINATION</th>
				<th>DATE</th>
				<th>TIME</th>
				<th>PRICE</th>
				<th>
					<center>ACTION</center>
				</th>
			</thead>
			<tbody>
				<?php
				$tid = $_SESSION['tourID'];
				$sql = "SELECT * FROM reservation r INNER JOIN motors b ON b.motor_id = r.motor_id
            INNER JOIN users t ON t.user_id = r.user_id
            WHERE t.user_id = ? AND r.status NOT IN ('approved', 'declined')";
				$res = $db->getRows($sql, [$tid]);

				// echo print_r($res);

				foreach ($res as  $r) {

					$r_id = $r['r_id'];
					$img = $r['b_img'];
					$bn = $r['b_name'];
					$bon = $r['b_model'];
					$dstntn = $r['r_dstntn'];
					$bprice = $r['b_price'];
					$rdate = $r['r_date'];
					$rhr = $r['r_hr'];
					$rampm = $r['r_ampm'];

					$oras = $rhr . ' ' . $rampm;
				?>
					<tr>
						<td class="align-img">
							<center><img src="<?php echo $img; ?>" width="50" height="50"></center>
						</td>
						<td class="align-img"><?php echo $bn; ?></td>
						<td class="align-img"><?php echo $bon; ?></td>
						<td class="align-img"><?php echo $dstntn; ?></td>
						<td class="align-img"><?php echo $rdate; ?></td>
						<td class="align-img"><?php echo $oras; ?></td>
						<td class="align-img"><?php echo 'Php ' . number_format($bprice, 2); ?></td>
						<td class="align-img">
							<a class="btn btn-danger  btn-xs" href="myreservation.php?delr_id=<?php echo $r_id; ?>">
								Cancel
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</a>
						</td>
					</tr>
				<?php
				} //end foreach loop of display all resevdbation


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
<link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css"><!--searh box positioning-->
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