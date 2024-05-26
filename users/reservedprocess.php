<?php 
	include_once('../config.php');
	$db = new Database();
?>

<?php 
	if(isset($_POST['bid'])) {
		$bid = $_POST['bid'];
		$tid = $_POST['tid'];
		$dt = $_POST['dstntn'];
		$date = $_POST['rdate'];
		$hr = $_POST['hr'];
		$ampm = $_POST['ampm'];

		if(!$dt) {
			echo '
				<div class="alert alert-danger">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <strong>Warning!</strong> Destination is Required.
				</div>
			';
		} else if(!$date) {
			echo '
				<div class="alert alert-danger">
				  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				  <strong>Warning!</strong> Date is Required.
				</div>
			';
		} else {
			// Check if reservation limit for the boat and date has been reached
			$sql = "SELECT COUNT(r_date) as rdt FROM reservation WHERE motor_id = ? AND r_date = ?";
			$res = $db->getRow($sql, [$bid, $date]);
			
			if($res['rdt'] > 2) {
				echo '
					<div class="alert alert-danger">
					  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					  <strong>Warning!</strong> Reservation limit for this boat has been reached.
					</div>
				';
			} else {
				// Check if the reservation already exists for the given boat, date, hour, and AMPM
				$sql = 'SELECT * FROM reservation WHERE motor_id = ? AND r_date = ? AND r_hr = ? AND r_ampm = ?';
				$res = $db->getRows($sql, [$bid, $date, $hr, $ampm]);
				
				if($res) {
					foreach ($res as $r) {
						$rbid = $r['motor_id'];
						$rd = $r['r_date'];
						$rh = $r['r_hr'];
						$rampm = $r['r_ampm'];
						
						if(($rd == $date) AND ($rh == $hr) AND ($rampm == $ampm) AND ($rbid == $bid)) {
							echo '
								<div class="alert alert-danger">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  <strong>Warning!</strong> This reservation has already been made.
								</div>
							';
						}
					}
				} else {
					// Insert the reservation into the database
					$sql = "INSERT INTO reservation (r_dstntn, r_hr, r_ampm, motor_id, user_id, r_date)
							VALUES (?, ?, ?, ?, ?, ?)";
					$res = $db->insertRow($sql, [$dt, $hr, $ampm, $bid, $tid, $date]);

					if($res) {
						echo '
							<div class="alert alert-success">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Success!</strong> Reservation has been made successfully.
							</div>
						';
					} else {
						echo '
							<div class="alert alert-danger">
							  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							  <strong>Error!</strong> Failed to make reservation.
							</div>
						';
					}
				}
			}
		}
	}
?>