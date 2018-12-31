<?php
include ("../logistics/log-header.php"); 

if (isset($_GET['eid'])) {
	$new = 0;
	$event_id = $_GET['eid'];
	
	$stmt = $conn->prepare("SELECT * FROM schedule_events WHERE event_id = :event_id");
	$stmt->bindValue(':event_id', $event_id);
	$stmt->execute();
	$event = $stmt->fetch(PDO::FETCH_ASSOC);
	$event_id = $event['event_id'];
}
else 
{
	$new = 1;	
	$stmt = $conn->prepare("INSERT INTO schedule_bus_run (bus_run_id) VALUES (NULL);");
	$stmt->execute();
	$type_id = $conn -> lastInsertId();
	
	$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
	$stmt->bindValue(':event_date', $_SESSION['date']);
	$stmt->bindValue(':event_type', "B");
	$stmt->bindValue(':event_type_id', $type_id);
	$stmt->execute();
	$event_id = $conn -> lastInsertId();
}

$stmt = $conn->prepare("SELECT * FROM schedule_events 
						LEFT JOIN schedule_bus_run ON schedule_events.event_type_id = schedule_bus_run.bus_run_id
						LEFT JOIN ss_routes ON ss_routes.route_id = schedule_bus_run.bus_run_route
						WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$type_name = "bus_run";
?>

<link rel="stylesheet" href="_edit.css">

<h1><?php if ($new == 0) {print "Edit";} else {print "New";} ?> Bus Run</h1>

<form id="bus-run-form" class="pure-form pure-form-stacked" method=post>
	<div class="pure-g">
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3  pure-u-lg-1-4">
			<div class="input-padding">
				<label>Run Date</label>
				<input class="pure-input-1 gl-input-date" type="date" name="event_date" onkeypress="return noenter()" value="<?php print $event['event_date']; ?>">
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3  pure-u-lg-1-4">
			<div class="input-padding">
				<label>Route</label>
				<select class="pure-input-1 gl-input" name="bus_run_route">
					<option value="" disabled selected>Select...</option>
					<?php
						$routesquery = "SELECT * FROM ss_routes ORDER BY route_sort";
						foreach ($conn->query($routesquery) as $route)
						{
							print "<option value=".$route['route_id'];
							if ($route['route_id'] == $event['bus_run_route'])
								{print " selected";}
							print ">".$route['route_description']."</option>";
						}
					?>
				</select>
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3  pure-u-lg-1-8">
			<div class="input-padding">
				<label>Departure Time</label>
				<input class="pure-input-1 gl-input" type="time" name="bus_run_departure_time" value="<?php print $event['bus_run_departure_time']; ?>"/>
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3  pure-u-lg-1-8">
			<div class="input-padding">
				<label>Return Time</label>
				<input class="pure-input-1 gl-input" type="time" name="bus_run_return_time" value="<?php print $event['bus_run_return_time']; ?>"/>
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3  pure-u-lg-1-8">
			<div class="input-padding">
				<label>Carrier</label>
				<select class="pure-input-1 gl-input" class="option" name="bus_run_carrier">
					<option value="1" <?php	if($event['bus_run_carrier']==1){print " selected";} ?>>Yes</option>
					<option value="0" <?php	if($event['bus_run_carrier']==0){print " selected";} ?>>No</option>
				</select>
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3  pure-u-lg-1-8">
			<div class="input-padding">
				<label># of Busses</label>
				<input class="pure-input-1 gl-input" type="text" name="bus_run_number" value="<?php print $event['bus_run_number']; ?>" />
			</div>
		</div>
		<div class="pure-u-1">
			<div class="input-padding">
				<label>Program</label>
				<input class="pure-input-1 gl-input" type="text" name="bus_run_program" value="<?php print $event['bus_run_program']; ?>" />
			</div>
		</div>
		<div class="pure-u-1">
			<div class="input-padding">
				<label>Notes</label>
				<textarea class="pure-input-1 gl-input" type="text" name="bus_run_notes"><?php print $event['bus_run_notes']; ?></textarea>
			</div>
		</div>	
		<div class="pure-u-1">
			<hr />
		</div>
		<div class="pure-u-1 pure-u-sm-1-3">
			<div class="input-padding">
				<label>Title for Return Time to LCVI</label>
				<input class="pure-input-1 gl-input" type="text" name="bus_run_lcvi_title" value="<?php print $event['bus_run_lcvi_title']; ?>" />
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-3">
			<div class="input-padding">
				<label>Expected Return Time to LCVI</label>
				<input class="pure-input-1 gl-input" type="text" name="bus_run_lcvi_expect" value="<?php print $event['bus_run_lcvi_expect']; ?>" />
			</div>
		</div>
		<div class="pure-u-1 pure-u-sm-1-3">
			<div class="input-padding">
				<label>Updated Return Time to LCVI</label>
				<input class="pure-input-1 gl-input" type="text" name="bus_run_lcvi_update" value="<?php print $event['bus_run_lcvi_update']; ?>" />
			</div>
		</div>
		<div class="pure-u-1">
			<div class="input-padding">
				<label>Notes for Bus Return Page</label>
				<textarea class="pure-input-1 gl-input" type="text" name="bus_run_lcvi_notes"><?php print $event['bus_run_lcvi_notes']; ?></textarea>
			</div>
		</div>	
		<div class="pure-u-1"style="text-align: center;">
			<button id="complete" class="plaintext-button">complete</button>
			<button id="delete" class="plaintext-button"><?php if ($new == 0) {print "delete";} else {print "cancel";} ?></button>
		</div>				
	</form>
</div>
<?php include("edit-js.php"); ?>