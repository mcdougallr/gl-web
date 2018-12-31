<?php
if(!isset($_SESSION)) {
     session_start();
}
	
if ($_SESSION['refer'] == "sy") {include ("../school-year/sy-header.php");}
else {include ("../school-year-admin/sya-header.php");}

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
	$stmt = $conn->prepare("INSERT INTO schedule_task (task_id) VALUES (NULL);");
	$stmt->execute();
	$type_id = $conn -> lastInsertId();
	
	$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
	$stmt->bindValue(':event_date', $_SESSION['date']);
	$stmt->bindValue(':event_type', "T");
	$stmt->bindValue(':event_type_id', $type_id);
	$stmt->execute();
	$event_id = $conn -> lastInsertId();
}

$stmt = $conn->prepare("SELECT * FROM schedule_events 
						LEFT JOIN schedule_task ON schedule_events.event_type_id = schedule_task.task_id
						LEFT JOIN sy_tasks ON sy_tasks.task_name_id = schedule_task.task_name_id
						WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$type_name = "task";

?>

<link rel="stylesheet" href="_edit.css">

<h2><?php if ($new == 0) {print "Edit";} else {print "New";} ?> Task</h2>

<div class="pure-g" style="padding-top: 5px;">
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">
		<form id="task-form" class="pure-form pure-form-stacked" method=post>
			<div class="pure-g">
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>Task Date</label>
						<input class="pure-input-1 gl-input-date" type="date" name="event_date" onkeypress="return noenter()" value="<?php print $event['event_date']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2"">
					<div class="input-padding">
						<label>Task</label>
						<select class="pure-input-1 gl-input" class="option" name="task_name_id">
							<option value="" disabled selected>Select...</option>
							<?php
								$taskquery = "SELECT * FROM sy_tasks ORDER BY sort_order";
								foreach ($conn->query($taskquery) as $task_name)
								{
									print "<option value=".$task_name['task_name_id'];
									if ($task_name['task_name_id'] == $event['task_name_id'])
										{print " selected";}
									print ">".$task_name['task_name']."</option>";}
							?>
						</select>
					</div>
				</div>
				<div class="pure-u-1">
					<div class="input-padding">
						<label>Task Notes</label>
						<textarea class="pure-input-1 gl-input textarea_expand" type="text" name="task_notes"><?php print $event['task_notes']; ?></textarea>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
		<div class="input-padding">
			<?php include ('edit-staffing-sy.php'); ?>
		</div>
	</div>
	<div class="pure-u-1"style="text-align: center;">
		<button id="complete" class="plaintext-button">complete</button>
		<button id="delete" class="plaintext-button"><?php if ($new == 0) {print "delete";} else {print "cancel";} ?></button>
	</div>				
</div>
<?php include("edit-js.php"); ?>
<!--