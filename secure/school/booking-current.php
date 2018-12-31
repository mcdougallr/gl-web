<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

//Date variables
$date = date('Y-m-d');
$month = date('m');
$year = date('Y');

//Get school info
$stmt = $conn->prepare("SELECT * FROM sy_schools WHERE school_id = :school_id");
$stmt->bindValue(':school_id', $_SESSION['school_id']);
$stmt->execute();
$school = $stmt->fetch(PDO::FETCH_ASSOC);

//See if booking is open
$stmt = $conn->prepare("SELECT season_name, booking_start, booking_end FROM sy_seasons WHERE DATE_FORMAT(:date, '%m-%d') between booking_start AND booking_end");
$stmt->bindValue(':date', $date);
$stmt->execute();
$booking_open = $stmt->fetch(PDO::FETCH_ASSOC);
if ($booking_open != "") {$book = 1;}
else {$book = 0;}

//See what season is coming up next
$stmt = $conn->prepare("SELECT booking_start, season_name, next_start, next_end FROM sy_seasons WHERE DATE_FORMAT(:date, '%m-%d') between next_start AND next_end");
$stmt->bindValue(':date', $date);
$stmt->execute();
$nextseason = $stmt->fetch(PDO::FETCH_ASSOC);
$booking_start = $year."-".$nextseason['booking_start'];

if ($nextseason['season_name'] == "Spring") {$quota = $school['S_quota']; $program_season = "FS";}
elseif ($nextseason['season_name'] == "Fall") {$quota = $school['F_quota']; $program_season = "FS";}
elseif ($nextseason['season_name'] == "Winter") {$quota = $school['W_quota']; $program_season = "W";}
else {$quota = ""; $program_season = "";}

//Get submitted visit data for upcoming season
//Calculate spots remaining from quota
$stmt = $conn->prepare("SELECT * FROM schedule_events
						LEFT JOIN schedule_visit ON schedule_events.event_type_id = schedule_visit.visit_id
						LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
					   	WHERE school_id = :school_id AND submit_date >= :submit_date AND event_type = :event_type");
$stmt->bindValue(':school_id', $_SESSION['school_id']);
$stmt->bindValue(':submit_date', $booking_start);
$stmt->bindValue(':event_type', "V");
$stmt->execute();
$visits = $stmt->fetchAll();
$visitcount = count($visits);

$count = 0;

if ($book != 0)
{
	?>
	<hr />
	<h2>Submitted <?php print $nextseason['season_name']; ?> Bookings</h2>
	
	<?php 
	$bg = "#EEE";
	if ($visitcount > 0) 
	{ ?>		
		<p> You have currently submitted <?php print $visitcount; ?> bookings.<br /><?php print $school['school_name']; ?> has a quota of <?php print $quota; ?> for this booking period.</p>
		<table class="visit-table pure-table pure-table-bordered">
			<tr>
				<th style="text-align: left;">Date Submitted</th>
				<th>Teacher</th>
				<th>Grade</th>
				<th>Program</th>
				<?php if ($book == 1){print "<th></th>";} ?>
			</tr>
			<?php
			foreach ($visits as $visit)
			{
				if ($bg == "#EEE") {$bg = "#FFF";} else {$bg = "#EEE";}?>
				<tr>        	
					<td style="text-align: left;background: <?php print $bg; ?>;"><?php print date('M d, Y', strtotime($visit['submit_date']));?></td>
					<td style="background: <?php print $bg; ?>;"><?php print $visit['teacher_name'];?></td>
					<td style="background: <?php print $bg; ?>;"><?php print $visit['grade']; ?></td>
					<td style="background: <?php print $bg; ?>;"><?php print $visit['program_name']; ?></td>
					<?php if ($book == 1)
					{?>
						<td style="background: <?php print $bg; ?>;" rowspan=2>
							<button class="plaintext-button edit-button" id="edit-booking-button-<?php print $visit['event_id']; ?>"><i class="fa fa-pencil"></i> edit</button>
							<button class="plaintext-button delete-button" id="delete-booking-button-<?php print $visit['event_id']; ?>"><i class="fa fa-trash"></i> delete</button>
						</td>
					<?php
					}
					$count++;?>
				</tr>
				<tr>
					<td style="background: <?php print $bg; ?>;text-align: left;" colspan=4>
						Notes: <?php print $visit['visit_notes']; ?>
					</td>
				</tr>
						
			<?php
			}
			?>
		</table>
		<br />
	<?php }
	else
	{ ?>
		<p>You have not submitted any classes for this booking period.<br /><?php print $school['school_name']; ?> has a quota of <?php print $quota; ?> for this booking period.</p>
	<?php 
	} 
}
?>
<script>
	$(document).ready(function(){
		$('.edit-button').click(function(e) {
			e.preventDefault;
			id = $(this).attr("id");
			event_id = id.replace("edit-booking-button-", "");
			//alert(event_id);return false;
			$('#booking-edit').fadeOut('fast');
			$('#booking-edit').load('booking-form.php',{'eid': event_id},function(){
				$('#booking-edit').fadeIn('fast');
				$("#main").animate({ scrollTop: 0 }, "slow");
			});
		});
		
		$('.delete-button').click(function(e) {
			e.preventDefault;
			if (confirm("Are you sure you want to delete this booking?") == true)
			{
				id = $(this).attr("id");
				event_id = id.replace("delete-booking-button-", "");
				//alert(event_id); return false;
				$.ajax({
					type: "POST",
					url: "process-delete-visit.php",
					data: {event_id: event_id},
					success: function() 
					{
						$('#booking-edit').load('booking-form.php','',function(){
							$('#booking-edit').fadeIn('fast');
						});
						$('#current-bookings').load('booking-current.php','',function(){
							$('#current-bookings').fadeIn('fast');
						});	
					}
				});	
			}
		});					
	});	
</script>