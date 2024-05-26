<?php
include_once('../config.php'); // Database connection
$db = new Database();
$tid = $_SESSION['tourID']; // Assuming user ID is stored in session// Fetch logged-in user's username
$sql = "SELECT user_name FROM users WHERE user_id = ?";
$user_result = $db->getRow($sql, [$tid]);
$loggedInUser = $user_result['user_name'];
// Fetch reservation count excluding 'approved', 'declined', and 'deleted' statuses
$sql = "SELECT COUNT(*) AS reservation_count FROM reservation WHERE status IS NULL OR status NOT IN ('approved', 'declined', 'deleted')";
$result = $db->getRow($sql);
$reservation_count = $result['reservation_count'];
?>
<script>
function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete this user account?')) {
        // If user confirms, redirect to the deletion URL
        window.location.href = 'users.php?delid=' + userId;
    }
}
</script>

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

    <!-- Pagination -->
    <link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css">
    <script src="../bootstrap/js/jquery.dataTables2.js"></script>
</head>

<body>
    <!-- Begin whole content -->
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
                            <span class="notification"><?php echo $reservation_count; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="approved.php">Approved</a>
                    </li>
                    <li>
                        <a href="declined.php">Declined</a>
                    </li>
                    <li class="active">
                        <a href="users.php">Users</a>
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
    <!-- End -->

    <br /><br /><br /><br />

    <!-- Main content -->
    <?php
    // Handle deletion
    if (isset($_GET['delid'])) {
        $user_id = $_GET['delid'];
        $sql = "DELETE FROM users WHERE user_id = ? ";
        $res = $db->deleteRow($sql, [$user_id]);

        header('Location: users.php');
    }
    ?>

    <div class="container">
        <a href="register.php" class="btn btn-success">
            New
            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
        </a>
        <br /><br />

        <table id="myTable" class="table table-striped">
            <thead>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Username</th>
                <th>User Type</th>
                <th>
                    <center>Action</center>
                </th>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM users ORDER BY user_fN";
                $res = $db->getRows($sql);
                foreach ($res as $row) {
                    $user_id = $row['user_id'];
                    $user_fN = $row['user_fN'];
                    $user_mN = $row['user_mN'];
                    $user_lN = $row['user_lN'];
                    $user_address = $row['user_address'];
                    $tour_contact = $row['tour_contact'];
                    $user_name = $row['user_name'];
                    $user_type = $row['user_type'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user_fN); ?></td>
                        <td><?php echo htmlspecialchars($user_mN); ?></td>
                        <td><?php echo htmlspecialchars($user_lN); ?></td>
                        <td><?php echo htmlspecialchars($user_address); ?></td>
                        <td><?php echo htmlspecialchars($tour_contact); ?></td>
                        <td><?php echo htmlspecialchars($user_name); ?></td>
                        <td><?php echo htmlspecialchars($user_type); ?></td>
                        <!-- Inside the <td> tag where the delete button is located -->
<td>
    <center>
        <a class="btn btn-success btn-xs" href="userupdate.php?editid=<?php echo $user_id; ?>">
            Edit
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
        </a>
        <a class="btn btn-danger btn-xs" href="#" onclick="confirmDelete(<?php echo $user_id; ?>)">
            Delete
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </a>
    </center>
</td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- End Main content -->

    <script src="../bootstrap/js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/js/dataTables.js"></script>
    <script src="../bootstrap/js/dataTables2.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <!-- Pagination -->
    <link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css">
    <script src="../bootstrap/js/jquery.dataTables2.js"></script>

    <script>
        // Script for pagination
        $(document).ready(function() {
            $('#myTable').dataTable();
        });
    </script>
</body>

</html>

<?php
$db->Disconnect();
?>