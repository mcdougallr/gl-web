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
	$stmt = $conn->prepare("INSERT INTO schedule_visit (visit_id) VALUES (NULL);");
	$stmt->execute();
	$type_id = $conn -> lastInsertId();
	
	$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
	$stmt->bindValue(':event_date', $_SESSION['date']);
	$stmt->bindValue(':event_type', "V");
	$stmt->bindValue(':event_type_id', $type_id);
	$stmt->execute();
	$event_id = $conn -> lastInsertId();
}

$stmt = $conn->prepare("SELECT * FROM schedule_events 
						LEFT JOIN schedule_visit ON schedule_events.event_type_id = schedule_visit.visit_id
						LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
						LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
						WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$type_name = "visit";
$divisiondata = array("-",'P','P/J','J','J/I','I','S');

?>

<link rel="stylesheet" href="_edit.css">

<h2><?php if ($new == 0) {print "Edit";} else {print "New";} ?> Visit</h2>

<div class="pure-g" style="padding-top: 5px;">
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-3-5">
		<form id="visit-form" class="pure-form pure-form-stacked" method=post>
			<div class="pure-g">
				<div class="pure-u-1 pure-u-sm-1-3 pure-u-md-1-2 pure-u-lg-1-3">
					<div class="input-padding">
						<label>Visit Date</label>
						<input class="pure-input-1 gl-input-date" type="date" name="event_date" onkeypress="return noenter()" value="<?php print $event['event_date']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3 pure-u-md-1-2 pure-u-lg-1-3">
					<div class="input-padding">
						<label>School Name</label>
						<select id="school_id" class="pure-input-1 gl-input" name="school_id">
							<option value="" disabled selected>Select...</option>
							<?php
								$schoolquery = "SELECT * FROM sy_schools ORDER BY school_div, school_name";
								foreach ($conn->query($schoolquery) as $school)
								{
									print "<option value=".$school['school_id'];
									if ($school['school_id'] == $event['school_id'])
										{print " selected";}
									print ">".$school['school_name']."</option>";}
							?>
						</select>
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3 pure-u-md-1-2 pure-u-lg-1-3">
					<div class="input-padding">
						<label>Program</label>
						<select class="pure-input-1 gl-input" class="option" name="program_id">
							<option value="" disabled selected>Select...</option>
							<?php
								$programquery = "SELECT * FROM sy_programs WHERE visible_admin = 'Y' ORDER BY sort_order";
								foreach ($conn->query($programquery) as $program)
								{
									print "<option value=".$program['program_id'];
									if ($program['program_id'] == $event['program_id'])
										{print " selected";}
									print ">".$program['program_name']."</option>";}
							?>
						</select>
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3 pure-u-md-1-2 pure-u-lg-1-3">
					<div class="input-padding">
						<label>Teacher Name</label>
						<input class="pure-input-1 gl-input" type="text" name="teacher_name" value="<?php print $event['teacher_name']; ?>"/>
					</div>
				</div>
				<div class="pure-u-1-2 pure-u-sm-1-6 pure-u-md-1-4  pure-u-lg-1-6">
					<div class="input-padding">
						<label>Grade</label>
						<input class="pure-input-1 gl-input" type="text" name="grade" value="<?php print $event['grade']; ?>" />
					</div>
				</div>
				<div class="pure-u-1-2 pure-u-sm-1-6 pure-u-md-1-4 pure-u-lg-1-6">
					<div class="input-padding">
						<label>Division</label>
						<select class="pure-input-1 gl-input" class="option" name="division">
							<?php	
								foreach ($divisiondata as $division)
									{
										print "<option value=".$division;
										if ($division == $event['division'])
											{print " selected";}
										print ">".$division."</option>";}
							?>
						</select>
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-3 pure-u-md-1-2 pure-u-lg-1-3">
					<div class="input-padding">
						<label># of Students</label>
						<input class="pure-input-1 gl-input" type="text" name="student_num" value="<?php print $event['student_num']; ?>" />
					</div>
				</div>
			</div>
			<div class="pure-u-1">
				<div class="input-padding">
					<label>Notes</label>
					<textarea class="pure-input-1 textarea_expand gl-input" type="text" name="visit_notes"><?php print $event['visit_notes']; ?></textarea>
				</div>				
			</div>
		</form>
	</div>
	<div class="pure-u-1 pure-u-md-1-2 pure-u-lg-2-5">
		<div class="input-padding">
			<?php include ('edit-staffing-sy.php'); ?>
		</div>
	</div>
	<div class="pure-u-1" style="text-align: center;">
		<button id="complete" class="plaintext-button">complete</button>
		<button id="delete" class="plaintext-button"><?php if ($new == 0) {print "delete";} else {print "cancel";} ?></button>
	</div>				
</div>
<?php include("edit-js.php"); ?>