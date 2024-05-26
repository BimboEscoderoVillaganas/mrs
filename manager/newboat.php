<?php
include_once('../config.php'); // Including database configuration
$db = new Database(); // Creating database instance

$tid = $_SESSION['tourID']; // Assuming user ID is stored in session

// Fetch logged-in user's username
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['user_name'];
// Handle boat insertion
if (isset($_POST['insertmotor'])) {
    $bname = $_POST['bN'];
    $bon = $_POST['bON'];
    $bcpcty = $_POST['bC'];
    $bPrice = $_POST['b_price'];

    $new_image_name = 'image_' . date('Y-m-d-H-i-s') . '_' . uniqid() . '.jpg';
    move_uploaded_file($_FILES["bimg"]["tmp_name"], "../motors_image/".$new_image_name);
    $new_image_name = '../motors_image/'.$new_image_name;

    $sql = "INSERT INTO motors (b_name, m_quantity, b_model, b_img, b_price) VALUES (?, ?, ?, ?, ?)";
    $res = $db->insertRow($sql, [$bname, $bcpcty, $bon, $new_image_name, $bPrice]);

    echo '
        <script>
            alert("Motorcycle added successfully!");
            window.location.href = "index.php";
        </script>
    ';
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
        </div>
    </div>
</nav>
<!-- End Navbar -->

<!-- Main Content -->
<div class="container-fluid">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        
        <br />
        <br /><br><br>

        <!-- Boat Insertion Form -->
				<a href="index.php" class="btn btn-success">
					Back
					<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
				</a>
			<br />
			<br />
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputdefault">Brand Name:</label>
                <input class="form-control" id="inputdefault" name="bN" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Model:</label>
                <input class="form-control" id="inputdefault" name="bON" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Quantity:</label><br />
                <input type="text" name="bC" class="btn-lg" style="width:250px;" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Image:</label>
                <input class="form-control" id="inputdefault" name="bimg" type="file" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Price:</label><br />
                <input type="text" name="b_price" class="btn-lg" style="width:250px;" required>
            </div>
			<button class="btn btn-info" name = "insertmotor">
					  		Save
					  		<span class="glyphicon glyphicon-save" aria-hidden="true"></span>
					  </button>
        </form>
        <!-- End Boat Insertion Form -->
    </div>
    <div class="col-md-3"></div>
</div>
<!-- End Main Content -->



 		<script src="../bootstrap/js/jquery-1.11.1.min.js"></script>
 		<script src="../bootstrap/js/dataTables.js"></script>
 		<script src="../bootstrap/js/dataTables2.js"></script>
 		<script src="../bootstrap/js/bootstrap.js"></script>

	</body>
</html>