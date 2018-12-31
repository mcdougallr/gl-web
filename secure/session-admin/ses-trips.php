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

<h1><?php print $section['session_program_code'] . $section['session_number'] . " Trips"; ?><i id="trips-menu" class="fa fa-bars" style="font-size: .8em;margin-left: 5px;cursor: pointer;"></i></h1>

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
					print "<a href=\"ses-trips.php?sid=".$section_id."&tid=".$trip['trip_id']."\" class=\"plaintext-button\">".$trip['trip_name']."</a>";
					if ($trip['trip_id'] == $trip_id){print "<i style=\"font-size: 1.2em;color: #685191;margin-right: 3px;\" class=\"fa fa-angle-double-right\"></i>";}
					print "</div>";
				}
			?>
		</div>
	</div>
	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
		<div class="trip-detail">
			<h2>Trip Details</h2>
			<?php
			//Trip Name, putin, pickup, pickup time, Sat #, TT Sat #
			//Get trip info
			$stmt = $conn->prepare("SELECT * FROM fp_trips
															LEFT JOIN fp_sats ON fp_sats.sat_id = fp_trips.trip_sat
															WHERE trip_id = :trip_id");
			$stmt->bindValue(':trip_id', $trip_id);
			$stmt->execute();				
			$trip = $stmt->fetch(PDO::FETCH_ASSOC);
			//insert Form
			?>		
			<form id="trip-details-form" class="pure-form pure-form-stacked gl-input-form">
				<div class="input-padding">
					<label>Trip Name</label>
					<input class="pure-input-1 gl_input" name="trip_name" type="text" onconkeypress="return noenter()" value="<?php print $trip['trip_name']; ?>">
				</div>
				<div class="input-padding">
					<label>Drop-off Location</label>
					<input class="pure-input-1 gl_input" name="trip_d_loc" type="text" onconkeypress="return noenter()" value="<?php print $trip['trip_d_loc']; ?>">
				</div>
				<div class="input-padding">
					<label>Pickup Location</label>
					<input class="pure-input-1 gl_input" name="trip_p_loc" type="text" onconkeypress="return noenter()" value="<?php print $trip['trip_p_loc']; ?>">
				</div>
				<div class="input-padding">
					<label>Pickup Time</label>
					<input class="pure-input-1 gl_input" name="trip_p_time" type="text" onconkeypress="return noenter()" value="<?php print $trip['trip_p_time']; ?>">
				</div>
				<div class="input-padding">
					<label>Tent # / Colour</label>
					<input class="pure-input-1 gl_input" name="trip_tents" type="text" onconkeypress="return noenter()" value="<?php print $trip['trip_tents']; ?>">
				</div>
				<div class="input-padding">
					<label>Canoe # / Colour</label>
					<input class="pure-input-1 gl_input" name="trip_canoes" type="text" onconkeypress="return noenter()" value="<?php print $trip['trip_canoes']; ?>">
				</div>
				<div class="input-padding">
					<label>Sat Phone #</label>
					<select class="pure-input-1 gl_input" name="trip_sat">
						<option value="">Select Sat Number...</option>
						<?php
						$satquery = "SELECT * FROM fp_sats ORDER BY sat_id";
						foreach ($conn->query($satquery) as $sat)
						{
							print "<option value=".$sat['sat_id'];
							if ($trip['trip_sat'] == $sat['sat_id']){print " selected";}
							print ">".$sat['sat_name']." - ".$sat['sat_num']."</option>";
						}
						?>
					</select>
				</div>
				<div class="input-padding">
					<label>Twin Trip Sat Phone #</label>
					<select class="pure-input-1 gl_input" name="trip_sat_tt">
						<option value="">Select Sat Number...</option>
						<?php
						foreach ($conn->query($satquery) as $sat)
						{
							print "<option value=".$sat['sat_id'];
							if ($trip['trip_sat_tt'] == $sat['sat_id']){print " selected";}
							print ">".$sat['sat_name']." - ".$sat['sat_num']."</option>";
						}
						?>
					</select>
				</div>
			</form>
		</div>
	</div>
	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
		<div  class="trip-detail" style="background: #EEE;">
			<h2>Staff</h2>
			<div>
				<h3>Staff</h3>
				<?php
				//Staff Form
				$tt = 0;
				include ('ses-tripstaffing.php'); 
				?>
				<hr style="margin: 20px 0 15px;" />
				<h3>Twin Trip Staff</h3>
				<?php
				//TT Staff Form
				$tt = 1;
				include ('ses-tripstaffing.php'); 
				?>
			</div>
		</div>
	</div>
	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
		<div class="trip-detail">
		<h2>Student</h2>
			<div>
				<h3>Students</h3>
				<?php
				//Staff Form
				$extra = 0;
				include ('ses-tripstudents.php'); 
				?>
				<hr style="margin: 20px 0 15px;" />
				<h3>WICs/Volunteers</h3>
				<?php
				//TT Staff Form
				$extra = 1;
				include ('ses-tripstudents.php'); 
				?>
			</div>
		</div>
	</div>
	<div class="pure-u-1 pure-u-sm-1-2 pure-u-lg-1-4">
		<div class="trip-detail" style="background: #EEE;">
		<h2>Itinerary</h2>
			<?php
			//Trip Itinerary Form
			include ('ses-tripdays.php'); 
		?>
		</div>
	</div>
</div>

<script>

	$(document).ready(function(){

		// PROCESS FORM INPUT
		$('.gl_input').change(function(e) {
			trip_id = <?php print $trip_id; ?>;
			field_name = $(this).attr("name");
			field_val = $(this).val();
			$(this).css('background', '#685191');
			//alert(field_name+" "+field_val+" "+trip_id);
			$.ajax({
				type : "POST",
				url : "process-trip-input.php",
				data : {
					field_name : field_name,
					field_val : field_val,
					trip_id : trip_id
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
			event_id = $(this).attr("id").substring(4);
			field_val = $(this).val();
			$(this).css('background', '#685191');
			//alert(field_val+"-"+event_id);
			$.ajax({
				type : "POST",
				url : "process-section-date-input-date.php",
				data : {
					field_val : field_val,
					event_id: event_id
				},
				success : function() {
					$('.gl-input-date').delay(300)
				    .queue(function() {
				        $('.gl-input-date').css('background', 'transparent').dequeue();
				    });
				}
			});
		});

		$("#trips-menu").click(function() {
			$(".hide").toggle();
		});
		
	});

</script>
