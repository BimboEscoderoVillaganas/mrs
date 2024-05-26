<?php 

	include_once('../config.php');//database
	$db = new Database();
	
    $tid = $_SESSION['tourID']; // Assuming user ID is stored in session
    // Fetch logged-in user's username
    $sql = "SELECT user_name FROM users WHERE user_id = ?";
    $user_result = $db->getRow($sql, [$tid]);
    $loggedInUser = $user_result['user_name'];
// Fetch reservation count excluding 'approved', 'declined', and 'deleted' statuses
$sql = "SELECT COUNT(*) AS reservation_count FROM reservation WHERE status IS NULL OR status NOT IN ('approved', 'declined', 'deleted')";
$result = $db->getRow($sql);
$reservation_count = $result['reservation_count'];
?>

<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Motorcycle Reservation</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.dataTables.css">

	</head>
	<style type="text/css">
        .navbar { margin-bottom:0px !important; }
        .carousel-caption { margin-top:0px !important }

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
	<style type="text/css">
		.navbar { margin-bottom:0px !important; }
		.carousel-caption { margin-top:0px !important }
	</style>

	<body>

		<br />
		<br />
		<br />
		
	
			

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
							<li class="active">
								<a href="index.php">Motorcycles</a>
							</li>
							<li>
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
		<!-- end -->

		<br />

		
		<!-- main cntent -->
		<div class="container-fluid">

			<div class="col-md-3"></div>
			<div class="col-md-6">
				<a href="index.php" class="btn btn-success">
					Back
					<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
				</a>
			<br />
			<br />

			

			<form action="" method="POST" enctype="multipart/form-data">
                <?php 
                    // View boat
                    if(isset($_GET['editid'])) {
                        $editid = $_GET['editid'];

                        $sql = "SELECT * FROM motors WHERE motor_id = ?";
                        $res = $db->getRow($sql, [$editid]);
                        $bname = $res['b_name'];
                        $bon = $res['b_model'];
                        $bcpcty = $res['m_quantity'];
                        $getoldbimg = $res['b_img'];
                        $bPrice = $res['b_price'] ?? '';
                    }

                    // Update boat
                    if(isset($_POST['updateboat'])) {
                        $editid = $_POST['editid'];

                        $bname = $_POST['bN'];
                        $bon = $_POST['bON'];
                        $bcpcty = $_POST['bC'];
                        $oldbimg = $_POST['oldbimg'];
                        $bPrice = $_POST['b_price'];

                        $new_image_name = 'image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.jpg';
                        move_uploaded_file($_FILES["bimg"]["tmp_name"], "../motors_image/".$new_image_name);
                        $new_image_name = '../motors_image/'.$new_image_name;

                        if(empty($_FILES["bimg"]["tmp_name"])) {
                            $sql = "UPDATE motors SET b_name = ?, m_quantity = ?, b_model = ?, b_price = ? WHERE motor_id = ?";
                            $res = $db->updateRow($sql, [$bname, $bcpcty, $bon, $bPrice, $editid]);
                        } else {
                            $sql = "UPDATE motors SET b_name = ?, m_quantity = ?, b_model = ?, b_img = ?, b_price = ? WHERE motor_id = ?";
                            $res = $db->updateRow($sql, [$bname, $bcpcty, $bon, $new_image_name, $bPrice, $editid]);
                            if($oldbimg != '../motors_image/default.png') {
                                unlink($oldbimg);
                            }
                        }

                        echo '
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Success!</strong> Edit Successfully.
                            </div>
                        ';
                    }
                ?>

					   <div class="form-group">
					    <label for="inputdefault">Brand Name:</label>
					    <input class="form-control" id="inputdefault"  name="editid" type="hidden" value ="<?php echo $editid; ?>">
					    <input class="form-control" id="inputdefault"  name="bN" type="text" value ="<?php echo $bname; ?>">
					  </div>

					  <div class="form-group">
					    <label for="inputdefault">Model:</label>
					    <input class="form-control" id="inputdefault" name="bON" type="text" value ="<?php echo $bon; ?>">
					  </div>

					  <div class="form-group">
    <label for="inputdefault">Quantity:</label><br />
    <input type="text" name="bC" class="btn-lg" style="width:250px;" value="<?php echo htmlspecialchars($bcpcty); ?>">
</div>
					  <input type="hidden" name="oldbimg" value="<?php echo $getoldbimg; ?>">

					   <div class="form-group">
				    	  <label for="inputdefault">Image:</label>
					      <input class="form-control" id="inputdefault" name="bimg" type="file">
					    </div>

						<div class="form-group">
    <label for="inputdefault">Price:</label><br />
    <input type="text" name="b_price" class="btn-lg" style="width:250px;" value="<?php echo htmlspecialchars($bPrice); ?>">
</div>

					  <button class="btn btn-info" name = "updateboat">
					  		Save
					  		<span class="glyphicon glyphicon-save" aria-hidden="true"></span>
					  </button>
				</form>	
			</div>
			<div class="col-md-3"></div>
		</div>
		<!-- main cntent -->



 		<script src="../bootstrap/js/jquery-1.11.1.min.js"></script>
 		<script src="../bootstrap/js/dataTables.js"></script>
 		<script src="../bootstrap/js/dataTables2.js"></script>
 		<script src="../bootstrap/js/bootstrap.js"></script>

	</body>
</html>