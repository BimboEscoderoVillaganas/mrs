<?php 
    include_once('../config.php'); // Database connection
    session_start(); // Ensure session is started
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


<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MRS ADMIN</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.dataTables.css">
		<link rel="stylesheet" href="notif.css">

         <!--pagination-->
        <link rel="stylesheet" href="../bootstrap/css/jquery.dataTables.css"><!--search box positioning-->
        <script src="../bootstrap/js/jquery.dataTables2.js"></script>

    </head>

    

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
                            <li class="active">
                                <a href="index.php">Motorcycles</a>
                            </li>
                            <li>
                                <a href="reservation.php">Reservation 
                        <span class="notification"><?php echo $reservation_count; ?></span></a>
                                </a>
                            </li>
                            <li>
                                <a href="approved.php">Approved</a>
                            </li>
                            <li>
                                <a href="declined.php">Declined</a>
                            </li>
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
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
        <!-- end -->

        <br />
        <br />
        <br />
        <br />
        
        <!-- main content -->
        <?php 
            //para delete
            if(isset($_GET['delid']))
            {
                $bid = $_GET['delid'];
                $sql = "DELETE FROM motors WHERE motor_id = ? ";
                $res = $db->deleteRow($sql,[$bid]);

                $bimg = $_GET['bimg'];
                if($bimg != '../motors_image/'.'default.png'){
                    unlink($bimg);
                }
                //header('Location: index.php'$bimg.'.jpg'
            }
        ?>

         <div class="container">
            <a href="newboat.php" class="btn btn-success">
                New
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
            </a>
            <br />
            <br />

             <table id="myTable" class="table table-striped" >  
                <thead>
                    <th>BRAND NAME</th>
                    <th>QUANTITY</th>
                    <th>MODEL</th>
                    <th><center>MOTORCYCLE IMAGE</center></th>
                    <th>PRICE</th>
                    <th><center>ACTION</center></th>
                </thead>
                <tbody>
                    <?php 
                        $sql = "SELECT * FROM motors ORDER BY b_name";
                        $res = $db->getRows($sql);
                        foreach ($res as $row) {
                            $bid = $row['motor_id'];
                            $bn = $row['b_name'];
                            $bon = $row['b_model'];
                            $bcpcty = $row['m_quantity'];
                            $bimg = $row['b_img'];
                            $bPrice = $row['b_price'];
                    ?>
                    <tr>
                        <td class="align-img"><?php echo $bn; ?></td>
                        <td class="align-img"><?php echo $bcpcty; ?></td>
                        <td class="align-img"><?php echo $bon; ?></td>
                        <td class="align-img"><center><img src="<?php echo $bimg; ?>" width="50" height="50"></center></td>
                        <td class="align-img"><?php echo 'Php '.number_format($bPrice, 2); ?></td>
                        <td class="align-img">
                            <a class = "btn btn-success btn-xs" href="motorsupdate.php?editid=<?php echo $bid; ?>">
                                Edit
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </a>
                            <a class = "btn btn-danger  btn-xs" href="index.php?delid=<?php echo $bid; ?>&bimg=<?php echo $bimg; ?>">
                                Delete
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
