<?php
include_once('../config.php');
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
	<link rel="stylesheet" href="index.css">
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
                        <a href="myreservation.php">My Reservation</a></li>
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
    </nav><br><br><br><br><br><br><br>
    <!-- end -->

    <div class="search-container">
        <input type="text" id="search" class="form-control" placeholder="Search for motorcycles...">
    </div>

    <div id="info"></div>
    <!-- begin content -->
    <div class="container-fluid">

        <div class="panel panel-info">
            <div class="panel-heading">List of Motorcycles Available</div>
            <div class="panel-body">
                <div class="motorcycle-container" id="motorcycle-container">
                    <?php
                    $sql = 'SELECT * FROM motors ORDER BY b_name';
                    $res = $db->getRows($sql);
                    if ($res) {
                        foreach ($res as $r) {
                            $motor_id = $r['motor_id'];
                            $bName = $r['b_name'];
                            $bCap = $r['m_quantity'];
                            $bON = $r['b_model'];
                            $bImage = $r['b_img'];
                            $bPrice = $r['b_price'];
                    ?>
                            <div class="motorcycle-item" data-name="<?php echo $bName; ?>" data-model="<?php echo $bON; ?>">
                                <a href="#" data-toggle="modal" data-target="#myModal<?php echo $motor_id; ?>">
                                    <img class="img-rounded" src="<?php echo $bImage; ?>" alt="<?php echo $bName; ?>">
                                </a>
                                <div class="motorcycle-description">
                                    <strong>Brand Name: </strong><?php echo $bName; ?><br />
                                    <strong>Available Units: </strong><?php echo $bCap; ?><br />
                                    <strong>Price: </strong><?php echo 'Php ' . number_format($bPrice, 2); ?><br />
                                    <strong>Model: </strong><?php echo $bON; ?><br />
                                </div>
                            </div>

                            <!-- Modal -->
                            <div id="myModal<?php echo $motor_id; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <img src="<?php echo $bImage; ?>" height="250" width="250">
                                                </div>
                                                <div class="col-md-6">
                                                    <form>
                                                        <strong>Brand Name: </strong><?php echo $bName; ?><br />
                                                        <strong>Available Units: </strong><?php echo $bCap; ?><br />
                                                        <strong>Price: </strong><?php echo 'Php ' . number_format($bPrice, 2); ?><br />
                                                        <strong>Model: </strong><?php echo $bON; ?> <br />
                                                        <strong>Location: </strong> <br />
                                                        <input type="text" id="dstntn<?php echo $motor_id; ?>">
                                                        <br />
                                                        <strong>Schedule Date: </strong>&nbsp;
                                                        <br />
                                                        <input class="btn-default" id="rdate<?php echo $motor_id; ?>" size="30" name="rdate" type="date" autocomplete="off">
                                                        <br />
                                                        <strong>Schedule Time: </strong>
                                                        <br />
                                                        <select class="btn-default" id="hr">
                                                            <?php
                                                            // Generate options for 9 AM to 12 noon
                                                            for ($time = 9; $time <= 12; $time++) {
                                                            ?>
                                                                <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                                                            <?php
                                                            }

                                                            // Generate options for 1 PM to 5 PM
                                                            for ($time = 1; $time <= 5; $time++) {
                                                            ?>
                                                                <option value="<?php echo $time + 12; ?>"><?php echo $time; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <select class="btn-default" id="ampm">
                                                            <option value="AM">AM</option>
                                                            <option value="PM">PM</option>
                                                        </select>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Close
                                                <span class="glyphicon glyphicon-remove-sign"></span>
                                            </button>
                                            <input type="submit" value="Reserved" onclick="bogkot('<?php echo $motor_id; ?>')" class="btn btn-success" data-dismiss="modal">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- modal END -->
                    <?php
                        } //end foreach of select all motorcycles
                    } //
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- end content -->
    <script type="text/javascript">
        function boat(str) {
            // alert(str);
        }
    </script>

    <script src="../bootstrap/js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/js/dataTables.js"></script>
    <script src="../bootstrap/js/dataTables2.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>

</body>

</html>

<script type="text/javascript">
    function bogkot(str) {
        var dstntn = $('#dstntn' + str).val();
        var bid = str;
        var tid = '<?php echo $_SESSION['tourID']; ?>';
        var dstntn = $('#dstntn' + str).val();
        var rdate = $('#rdate' + str).val();
        var hr = $('#hr').val();
        var ampm = $('#ampm').val();

        var datas = "bid=" + bid + "&tid=" + tid + "&dstntn=" + dstntn + "&rdate=" + rdate + "&hr=" + hr + "&ampm=" + ampm;

        $.ajax({
            type: "POST",
            url: "reservedprocess.php",
            data: datas
        }).done(function(data) {
            $('#info').html(data);
        });
    }

    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#motorcycle-container .motorcycle-item').filter(function() {
                $(this).toggle($(this).data('name').toLowerCase().indexOf(value) > -1 || $(this).data('model').toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
