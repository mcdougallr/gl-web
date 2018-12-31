<?php
include ("../logistics/log-header.php"); 

if (isset($_GET['eid'])) {
	$new = 0;
	$event_id = $_GET['eid'];
}
else 
{
	$new = 1;
	$stmt = $conn->prepare("INSERT INTO schedule_drive (drive_id) VALUES (NULL);");
	$stmt->execute();
	$type_id = $conn -> lastInsertId();
	
	$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
	$stmt->bindValue(':event_date', $_SESSION['date']);
	$stmt->bindValue(':event_type', "D");
	$stmt->bindValue(':event_type_id', $type_id);
	$stmt->execute();
	$event_id = $conn -> lastInsertId();
}

$stmt = $conn->prepare("SELECT * FROM schedule_events 
						LEFT JOIN schedule_drive ON schedule_events.event_type_id = schedule_drive.drive_id
						WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$type_name = "drive";

?>

<link rel="stylesheet" href="_edit.css">

<h1><?php if ($new == 0) {print "Edit";} else {print "New";} ?> Drive Day</h1>

<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">
		<form id="drive-day-form" class="pure-form pure-form-stacked" method=post>
			<div class="pure-g">
				<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1 pure-u-lg-1-4">
					<div class="input-padding">
						<label>Drive Date</label>
						<input class="pure-input-1 gl-input-date" type="date" name="event_date" onkeypress="return noenter()" value="<?php print $event['event_date']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1 pure-u-lg-3-4">
					<div class="input-padding">
						<label>Duty</label>
						<input class="pure-input-1 gl-input" type="text" name="drive_duty" onkeypress="return noenter()" value="<?php print $event['drive_duty']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>Vehicle</label>
						<select class="pure-input-1 gl-input" class=option name="drive_vehicle">
							<option value="">None</option>
							<?php
								$vehiclequery = "SELECT * FROM gl_vehicles";
								foreach ($conn->query($vehiclequery) as $vehicle)
								{
									print "<option value=".$vehicle['vehicle_id'];
									if ($vehicle['vehicle_id'] == $event['drive_vehicle'])
										{print " selected";}
									print ">".$vehicle['vehicle_name']."</option>";
								}
							?>
						</select>
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>Trailer</label>
						<select class="pure-input-1 gl-input" class=option name="drive_trailer">
							<option value="">None</option>
							<?php
								$trailerquery = "SELECT * FROM gl_trailers";
								foreach ($conn->query($trailerquery) as $trailer)
								{
									print "<option value=".$trailer['trailer_id'];
									if ($trailer['trailer_id'] == $event['drive_trailer'])
										{print " selected";}
									print ">".$trailer['trailer_name']."</option>";
								}
							?>
						</select>
					</div>
				</div>
				<div class="pure-u-1">
					<div class="input-padding">
						<label>Notes</label>
						<textarea class="pure-input-1 gl-input" type="text" name="drive_notes"><?php print $event['drive_notes']; ?></textarea>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
		<div class="input-padding">
			<?php include ('edit-staffing.php'); ?>
		</div>
	</div>
	<div class="pure-u-1"style="text-align: center;">
		<button id="complete" class="plaintext-button">complete</button>
		<button id="delete" class="plaintext-button"><?php if ($new == 0) {print "delete";} else {print "cancel";} ?></button>
	</div>				
</div>
<?php include("edit-js.php"); ?>