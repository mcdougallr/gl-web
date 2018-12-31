
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
	$type = $event['event_type'];
}
else 
{
	if (isset($_GET['etype'])) {
		$type = $_GET['etype'];
	}
	else {header ("Location: index.php");}
	$new = 1;
	
	if ($type == "S") {$table = "schedule_supervision";$type_day_id = "supervision_id";}
	elseif ($type == "O") {$table = "schedule_office";$type_day_id = "office_id";}
	elseif ($type == "E") {$table = "schedule_eq";$type_day_id = "eq_id";}
	elseif ($type == "F") {$table = "schedule_food";$type_day_id = "food_id";}
	else {header ("Location: index.php");}
	
	$stmt = $conn->prepare("INSERT INTO {$table} ({$type_day_id}) VALUES (NULL);");
	$stmt->execute();
	$type_id = $conn -> lastInsertId();
	
	$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
	$stmt->bindValue(':event_date', $_SESSION['date']);
	$stmt->bindValue(':event_type', $type);
	$stmt->bindValue(':event_type_id', $type_id);
	$stmt->execute();
	$event_id = $conn -> lastInsertId();
	
}

if ($type == "S") {$type_name = "supervision"; $title = "Supervision";}
elseif ($type == "O") {$type_name = "office"; $title = "Office";}
elseif ($type == "E") {$type_name = "eq"; $title = "EQ";}
elseif ($type == "F") {$type_name = "food"; $title = "Food";}
else {header ("Location: index.php");}

$table = "schedule_".$type_name;
$type_day_id = $type_name."_id";
$type_title = $type_name."_title";
$type_notes = $type_name."_notes";

$stmt = $conn->prepare("SELECT * FROM schedule_events 
						LEFT JOIN {$table} ON schedule_events.event_type_id = {$table}.{$type_day_id}
						WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<link rel="stylesheet" href="_edit.css">

<h1><?php if ($new == 0) {print "Edit ";} else {print "New ";print $title." ";} ?>Day</h1>

<div class="pure-g">
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">
		<form id="gen-day-form" class="pure-form pure-form-stacked" method=post>
			<div class="pure-g">
				<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1 pure-u-lg-1-4">
					<div class="input-padding">
						<label><?php print $title." "; ?>Date</label>
						<input class="pure-input-1 gl-input-date" type="date" name="event_date" onkeypress="return noenter()" value="<?php print $event['event_date']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1 pure-u-lg-3-4">
					<div class="input-padding">
						<label><?php print $title." "; ?>Title</label>
						<input class="pure-input-1 gl-input" type="text" name="<?php print $type_title; ?>" value="<?php print $event[$type_title]; ?>"/>
					</div>
				</div>
				<div class="pure-u-1">
					<div class="input-padding">
						<label>Notes</label>
						<textarea class="pure-input-1 gl-input" type="text" name="<?php print $type_notes; ?>"><?php print $event[$type_notes]; ?></textarea>
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