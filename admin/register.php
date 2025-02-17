<?php 
include_once('../config.php');
$db = new Database();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boat Reservation</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/jquery.dataTables.css">
</head>

<style type="text/css">
    .navbar { margin-bottom:0px !important; }
    .carousel-caption { margin-top:0px !important }
</style>

<body>
<br />
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <?php 
            if(isset($_POST['submit'])){
                
                $fN = $_POST['fN'];
                $lN = $_POST['lN'];
                $mN = $_POST['mN'];
                $add = $_POST['add'];
                $cont =  $_POST['cont'];
                $uN =  $_POST['uN'];
                $pass1 = $_POST['pass1'];
                $pass2 = $_POST['pass2'];

                if($pass1 != $pass2){
                    echo '
                        <div class="alert alert-danger">
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                          <strong>Error!</strong> Passwords do not match.
                        </div>
                    ';
                } else {
                    $pass = md5($pass1);
                    $sql = '
                        INSERT INTO users(user_fN, user_mN, user_lN, user_address, tour_contact, user_name, user_pass, user_type)
                        VALUES(?,?,?,?,?,?,?, 3);
                    ';

                    $result = $db->insertRow($sql, [$fN, $mN, $lN, $add, $cont, $uN, $pass]);
                    if($result){
                        echo '
                            <div class="alert alert-success">
                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                              <strong>Success!</strong> Registration successful. You will be redirected to the login page shortly.
                            </div>
                        ';
                        echo '<script>
                            setTimeout(function(){
                                window.location.href = "users.php";
                            }, 3000);
                        </script>';
                        exit();
                    }
                }
            }
            ?>
            <div class="panel panel-info">
              <div class="panel-heading">Registration Process</div>
                  <div class="panel-body">
                     <form action="" method="post">
                        <div class="form-group">
                             <label for="fN">First Name:</label>
                             <input type="text" class="form-control" name="fN" id="fN" 
                             value="<?php if(isset($fN)){echo $fN;} ?>"
                             required autofocus>
                        </div>

                        <div class="form-group">
                             <label for="mN">Middle Name:</label>
                             <input type="text" class="form-control" name="mN" id="mN" 
                             value="<?php if(isset($mN)){echo $mN;} ?>"
                             >
                        </div>

                        <div class="form-group">
                             <label for="lN">Last Name:</label>
                             <input type="text" class="form-control" name="lN" id="lN" 
                             value="<?php if(isset($lN)){echo $lN;} ?>"
                             required>
                        </div>  

                        <div class="form-group">
                             <label for="add">Address:</label>
                             <input type="text" class="form-control" name="add" id="add" 
                             value="<?php if(isset($add)){echo $add;} ?>"
                             required>
                        </div>  


                        <div class="form-group">
                             <label for="cont">Contact #:</label>
                             <input type="text" class="form-control" name="cont" id="cont" 
                              value="<?php if(isset($cont)){echo $cont;} ?>"
                             required>
                        </div>

                        <div class="form-group">
                             <label for="uN">Username:</label>
                             <input type="text" class="form-control" name="uN" id="uN" 
                              value="<?php if(isset($uN)){echo $uN;} ?>"
                             required>
                        </div>  

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label for="pass1">Password:</label>
                                     <input type="password" class="form-control" name="pass1" id="pass1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label for="pass2">Confirm Password:</label>
                                     <input type="password" class="form-control" name="pass2" id="pass2" required>
                                </div>
                            </div>
                        </div>  

                        <button type="submit" name="submit" class="btn btn-info">
                            Submit
                            <span class="glyphicon glyphicon-check"></span>
                        </button>                     
                     </form>    
                  </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>

    <footer style="background-color: white;">
        <center>
            &copy; All rights reserved
        </center>
    </footer>

    <script src="bootstrap/js/jquery-1.11.1.min.js"></script>
    <script src="bootstrap/js/dataTables.js"></script>
    <script src="bootstrap/js/dataTables2.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>

</body>
</html>
<?php 
$db->Disconnect();
?>
