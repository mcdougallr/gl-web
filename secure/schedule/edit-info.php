<?php
if(!isset($_SESSION)) {
     session_start();
}

if ($_SESSION['refer'] == "log"){include ("../logistics/log-header.php");}
elseif ($_SESSION['refer'] == "sy"){include ("../school-year/sy-header.php");}
else {include ("../school-year-admin/sya-header.php");}

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
	$new = 1;
	
	$stmt = $conn->prepare("INSERT INTO schedule_info (info_id) VALUES (NULL);");
	$stmt->execute();
	$type_id = $conn -> lastInsertId();
	
	$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
	$stmt->bindValue(':event_date', $_SESSION['date']);
	$stmt->bindValue(':event_type', "I");
	$stmt->bindValue(':event_type_id', $type_id);
	$stmt->execute();
	$event_id = $conn -> lastInsertId();
	
}

$stmt = $conn->prepare("SELECT * FROM schedule_events 
						LEFT JOIN schedule_info ON schedule_events.event_type_id = schedule_info.info_id
						WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$type_name = "info";

?>

<link rel="stylesheet" href="_edit.css">

<h1><?php if ($new == 0) {print "Edit ";} else {print "New ";} ?>Info</h1>

<div class="pure-g">
	<div class="pure-u-1">
		<form id="gen-day-form" class="pure-form pure-form-stacked" method=post>
			<div class="pure-g">
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>Info Date</label>
						<input class="pure-input-1 gl-input-date" type="date" name="event_date" onkeypress="return noenter()" value="<?php print $event['event_date']; ?>">
					</div>
				</div>
				<div class="pure-u-1 pure-u-sm-1-2">
					<div class="input-padding">
						<label>Info Title</label>
						<input class="pure-input-1 gl-input" type="text" name="info_title" value="<?php print $event['info_title']; ?>"/>
					</div>
				</div>
				<div class="pure-u-1">
					<div class="input-padding">
						<label>Notes</label>
						<textarea class="pure-input-1 gl-input" type="text" name="info_notes"><?php print $event['info_notes']; ?></textarea>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="pure-u-1"style="text-align: center;">
		<button id="complete" class="plaintext-button">complete</button>
		<button id="delete" class="plaintext-button"><?php if ($new == 0) {print "delete";} else {print "cancel";} ?></button>
	</div>				
</div>
<?php include("edit-js.php"); ?>
<!--
<script>
   
	$(document).ready(function(){				
		
		// SET REFER PATH
		refer = "<?php print $_SESSION['refer']; ?>";
		if (refer == "sya") {page = "../school-year-admin/index.php";}
		if (refer == "log") {page = "../logistics/index.php";}
		else {page = "../school-year/index.php";}
		
		// PROCESS FORM INPUT
		$('.gl_input').change(function(e) {
			field_name = $(this).attr("name");
			field_val = $(this).val();
			$(this).css('background', '#39F');
			//alert(name+" "+val);
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					field_name : field_name,
					field_val : field_val,
					table_name : "schedule_info",
					id_name : "info_day_id",
					id_val: "<?php print $day['info_day_id']; ?>"
				},
				success : function() {
					$('.gl_input').delay(300)
				    .queue(function() {
				        $('.gl_input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
		$('.gl-input-date').change(function(e) {
			field_val = $(this).val();
			$(this).css('background', '#39F');
			//alert(field_val);
			$.ajax({
				type : "POST",
				url : "process-input-event-date.php",
				data : {
					field_val : field_val,
					event_id: "<?php print $event_id; ?>"
				},
				success : function() {
					$('.gl-input-date').delay(300)
				    .queue(function() {
				        $('.gl-input-date').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
		$("#complete").click(function (e) {
			e.preventDefault();
			window.setTimeout(function(){window.location.href = page;},250);
		});
		
		$("#delete").click(function (e) {
			e.preventDefault();
			$.ajax({
				type : "POST",
				url : "process-edit-delete.php",
				data : {
					type : "info",
					type_day_id: "<?php print $day['info_day_id']; ?>",
					event_id: "<?php print $event_id; ?>"
				},
				success : function() {
					window.location.href = page;
				}
			});
		});
		
	});

</script>