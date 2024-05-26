<?php
session_start();
include_once('../config.php'); // Including database configuration
$db = new Database(); // Creating database instance

$tid = $_SESSION['tourID']; // Assuming user ID is stored in session
// Fetch logged-in user's username
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['user_name'];

// Handle user insertion
if (isset($_POST['add_user'])) {
    $firstName = $_POST['user_fN'];
    $middleName = $_POST['user_mN'];
    $lastName = $_POST['user_lN'];
    $address = $_POST['user_address'];
    $contact = $_POST['tour_contact'];
    $username = $_POST['user_name'];
    $password = password_hash($_POST['user_pass'], PASSWORD_DEFAULT); // Hash the password for security
    $userType = $_POST['user_type'];

    $sql = "INSERT INTO users (user_fN, user_mN, user_lN, user_address, tour_contact, user_name, user_pass, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [$firstName, $middleName, $lastName, $address, $contact, $username, $password, $userType];
    $res = $db->insertRow($sql, $params);

    if ($res) {
        echo '
            <script>
                alert("User added successfully!");
                window.location.href = "users.php";
            </script>
        ';
    } else {
        echo '
            <script>
                alert("Failed to add user!");
                window.location.href = "add_user.php";
            </script>
        ';
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
    <title>MRS ADMIN</title>

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
                <li><a href="index.php">Motorcycles</a></li>
                <li><a href="reservation.php">Reservation <span class="notification"><?php echo $reservation_count; ?></span></a></li>
                <li><a href="approved.php">Approved</a></li>
                <li><a href="declined.php">Declined</a></li>
                <li class="active"><a href="users.php">Users</a></li>
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
        <br><br><br><br><br>
        <!-- User Insertion Form -->
        <a href="users.php" class="btn btn-success">
            Back
            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
        </a>
        <br />
        <br />
        <form action="add_user.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="inputdefault">First Name:</label>
                <input class="form-control" id="inputdefault" name="user_fN" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Middle Name:</label>
                <input class="form-control" id="inputdefault" name="user_mN" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Last Name:</label>
                <input class="form-control" id="inputdefault" name="user_lN" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Address:</label>
                <input class="form-control" id="inputdefault" name="user_address" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Contact:</label>
                <input class="form-control" id="inputdefault" name="tour_contact" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Username:</label>
                <input class="form-control" id="inputdefault" name="user_name" type="text" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Password:</label>
                <input class="form-control" id="inputdefault" name="user_pass" type="password" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">User Type:</label>
                <select class="form-control" name="user_type" required>
                    <option value="1">Admin</option>
                    <option value="2">Manager</option>
                    <option value="3">User</option>
                </select>
            </div>
            <button class="btn btn-info" name="add_user">
                Add User
                <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
            </button>
        </form>
        <br><br><br><br>
        <!-- End User Insertion Form -->
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

<?php
$db->Disconnect();
?>
