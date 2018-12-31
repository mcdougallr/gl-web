<?php
$page_title = "GL Section Edit";
include ("ses-header.php"); 
include ('../shared/clean.php');

if (isset ($_GET['sid'])) {$section_id = cleantext($_GET['sid']);}
else {header('Location: index.php');}

if (isset ($_GET['tid'])) {$trip_id = cleantext($_GET['tid']);}
else {$trip_id = 0;}

$stmt = $conn->prepare("SELECT * FROM ss_session_sections 
						LEFT JOIN ss_sessions ON ss_session_sections.section_session_id = ss_sessions.session_id
						WHERE section_id = :section_id");
$stmt->bindValue(':section_id', $section_id);
$stmt->execute();				
$section = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM fp_trips WHERE trip_section_id = :trip_section_id ORDER BY trip_id ASC");
$stmt->bindValue(':trip_section_id', $section_id);
$stmt->execute();				
$trips = $stmt->fetchAll();

?>
<style>
	.hide {
		display: none;
	}
	h2 {
		color: #222;
		font-size: 1.3em;
		font-variant: small-caps;
		font-weight: normal;
		margin: 0 auto;
		text-align: center;
		font-family: FTYS, Georgia, Verdana, serif;
		padding:  2px 5px; 
		width: 100%
	}
		h3 {
		color: #222;
		font-size: 1em;
		font-variant: small-caps;
		font-weight: normal;
		margin: 0 auto;
		text-align: left;
		font-family: FTYS, Georgia, Verdana, serif;
		padding:  2px 0; 
		text-decoration: underline;
		width: 100%;
		color: #685191;
	}
	.trip-detail {
		margin: 3px;
		padding: 5px;
		border: 1px solid #CCC;
		border-radius: 5px;
	}
	.staff-text,
	.student-text,
	.day-text {
		font-size: .8em;
		margin-left: 3px;
		color: #685191;
	}
	.delete-tripstaff,
	.delete-tripstudent,
	.delete-tripday {
		margin-left: 5px;
		cursor: pointer;
	}
</style>

<link rel="stylesheet" href="_ses-trips.css">

<h1><?php print $section['session_program_code'] . $section['session_number'] . " Debriefs"; ?><i id="trips-menu" class="fa fa-bars" style="font-size: .8em;margin-left: 5px;cursor: pointer;"></i></h1>

<div class="pure-g">
	<div class="pure-u-1 hide">
		<div style="background: #FFF;padding: 5px;text-align:center;width:300px;margin: 3px auto;border: 1px solid #222;border-radius: 3px;">
			<?php
				$first = 1;
				foreach($trips as $trip)
				{
					if ($first == 1 && $trip_id == 0) {$trip_id = $trip['trip_id'];$first = 0;}
					print "<div>";
					if ($trip['trip_id'] == $trip_id){print "<i style=\"font-size: 1.2em;color: #685191;margin-right: 3px;\" class=\"fa fa-angle-double-left\"></i>";}
					print "<a href=\"ses-debrief.php?sid=".$section_id."&tid=".$trip['trip_id']."\" class=\"plaintext-button\">".$trip['trip_name']."</a>";
					if ($trip['trip_id'] == $trip_id){print "<i style=\"font-size: 1.2em;color: #685191;margin-right: 3px;\" class=\"fa fa-angle-double-right\"></i>";}
					print "</div>";
				}
			?>
		</div>
	</div>
	<?php
		$stmt = $conn->prepare("SELECT * FROM fp_trips WHERE trip_id = :trip_id");
		$stmt->bindValue(':trip_id', $trip_id);
		$stmt->execute();				
		$trip = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = $conn -> prepare("SELECT staff_name_common FROM fp_tripstaff
													LEFT JOIN staff ON staff.staff_id = fp_tripstaff.tripstaff_staff_id
													WHERE tripstaff_trip_id = '$trip_id' AND tripstaff_tt = 0
													ORDER BY staff_name_common");
		$stmt -> execute();
		$stafflist = $stmt -> fetchAll();
		if ($stafflist == NULL) {$staffnames = "(Not Staffed)";}
		else {
			$staffnames = "(";
			$first = 1;
			foreach ($stafflist as $staff)
			{
				if ($first != 1) {$staffnames = $staffnames."/";}
				$staffnames = $staffnames.$staff['staff_name_common'];
				$first = 0;
			}
			$staffnames = $staffnames.")";
		}
	?>
	<div class="pure-u-1">
		<div class="trip-detail">
			<h2>Details <?php print $trip['trip_name']." ".$staffnames; ?></h2>
			<?php
			//Trip Name, putin, pickup, pickup time, Sat #, TT Sat #
			//Get trip info
			$stmt = $conn->prepare("SELECT * FROM fp_debriefs WHERE debrief_trip_id = :debrief_trip_id");
			$stmt->bindValue(':debrief_trip_id', $trip_id);
			$stmt->execute();				
			$debrief = $stmt->fetch(PDO::FETCH_ASSOC);
			//insert Form
			?>		
			<form id="trip-details-form" class="pure-form pure-form-stacked gl-input-form">
				<div class="input-padding">
					<label>Notes</label>
					<textarea class="pure-input-1 gl_input" name="debrief_notes"><?php print $debrief['debrief_notes']; ?></textarea>
				</div>
			</form>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){

		// PROCESS FORM INPUT
		$('.gl_input').change(function(e) {
			trip_id = <?php print $trip_id; ?>;
			debrief_id = <?php if ($debrief['debrief_id'] == ""){print 0;} else {print $debrief['debrief_id'];} ?>;
			field_val = $(this).val();
			$(this).css('background', '#685191');
			//alert(trip_id+" "+field_val+" "+debrief_id);
			$.ajax({
				type : "POST",
				url : "process-debrief-input.php",
				data : {
					field_val : field_val,
					trip_id : trip_id,
					debrief_id : debrief_id
				},
				success : function() {
					$('.gl_input').delay(300)
				    .queue(function() {
				        $('.gl_input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});

		$("#trips-menu").click(function() {
			$(".hide").toggle();
		});
		
	});

</script>
