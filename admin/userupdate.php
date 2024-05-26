<?php
session_start();
include_once('../config.php'); // Including database configuration
$db = new Database();

$tid = $_SESSION['tourID']; // Assuming user ID is stored in session
// Fetch logged-in user's username
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['user_name'];

// Handle user update
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $firstName = $_POST['user_fN'];
    $middleName = $_POST['user_mN'];
    $lastName = $_POST['user_lN'];
    $address = $_POST['user_address'];
    $contact = $_POST['tour_contact'];
    $username = $_POST['user_name'];
    $password = $_POST['user_pass']; // Retrieve password from the form
    $userType = $_POST['user_type'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $sql = "UPDATE users SET user_fN = ?, user_mN = ?, user_lN = ?, user_address = ?, tour_contact = ?, user_name = ?, user_pass = ?, user_type = ? WHERE user_id = ?";
        $params = [$firstName, $middleName, $lastName, $address, $contact, $username, $hashedPassword, $userType, $user_id];
    } else {
        $sql = "UPDATE users SET user_fN = ?, user_mN = ?, user_lN = ?, user_address = ?, tour_contact = ?, user_name = ?, user_type = ? WHERE user_id = ?";
        $params = [$firstName, $middleName, $lastName, $address, $contact, $username, $userType, $user_id];
    }

    $res = $db->updateRow($sql, $params);

    if ($res) {
        echo '
            <script>
                alert("User updated successfully!");
                window.location.href = "users.php";
            </script>
        ';
    } else {
        echo '
            <script>
                alert("Failed to update user!");
                window.location.href = "userupdate.php?editid=' . $user_id . '";
            </script>
        ';
    }
}

// Fetch user details for editing
if (isset($_GET['editid'])) {
    $user_id = $_GET['editid'];
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $user_result = $db->getRow($sql, [$user_id]);

    if (!$user_result) {
        echo '
            <script>
                alert("User not found!");
                window.location.href = "users.php";
            </script>
        ';
    }
} else {
    header('Location: users.php');
    exit();
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
        <!-- User Update Form -->
        <a href="users.php" class="btn btn-success">
            Back
            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
        </a>
        <br />
        <br />
        <form action="userupdate.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_result['user_id']); ?>">
            <div class="form-group">
                <label for="inputdefault">First Name:</label>
                <input class="form-control" id="inputdefault" name="user_fN" type="text" value="<?php echo htmlspecialchars($user_result['user_fN']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Middle Name:</label>
                <input class="form-control" id="inputdefault" name="user_mN" type="text" value="<?php echo htmlspecialchars($user_result['user_mN']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Last Name:</label>
                <input class="form-control"id="inputdefault" name="user_lN" type="text" value="<?php echo htmlspecialchars($user_result['user_lN']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Address:</label>
                <input class="form-control" id="inputdefault" name="user_address" type="text" value="<?php echo htmlspecialchars($user_result['user_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Contact:</label>
                <input class="form-control" id="inputdefault" name="tour_contact" type="phone" value="<?php echo htmlspecialchars($user_result['tour_contact']); ?>" required>
            </div>
            <div class="form-group">
                <label for="inputdefault">Username:</label>
                <input class="form-control" id="inputdefault" name="user_name" type="text" value="<?php echo htmlspecialchars($user_result['user_name']); ?>" required>
            </div>
            <!-- Display current password -->
            <div class="form-group">
                <label for="inputdefault">Current Password:</label>
                <input class="form-control" id="inputdefault" type="text"  value="<?php echo htmlspecialchars($user_result['user_pass']); ?>" required readonly >
            </div>
            <div class="form-group">
                <label for="inputdefault">User Type:</label>
                <select class="form-control" name="user_type" required>
                    <option value="1" <?php if ($user_result['user_type'] == 1) echo 'selected'; ?>>Admin</option>
                    <option value="2" <?php if ($user_result['user_type'] == 2) echo 'selected'; ?>>Manager</option>
                    <option value="3" <?php if ($user_result['user_type'] == 3) echo 'selected'; ?>>User</option>
                </select>
            </div>
            <button class="btn btn-info" name="update_user">
                Update
                <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
            </button>
        </form>
        <br><br><br><br>
        <!-- End User Update Form -->
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
