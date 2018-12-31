<?php include ("sya-header.php"); 

$_SESSION['refer'] = "sya";

?>

<link rel="stylesheet" href="_index.css">

<section id="day-calendar" class="page">
    <div class="pure-g">
      	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-2">
      		<div id="calendar"></div>
      		<div id="schedule"></div>
    	</div>
    	<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-2">
			<div id="booking-list">
				<h1>Current Unscheduled Classes</h1>
				<hr />
				<?php
				$schoolquery = "SELECT * FROM sy_schools WHERE population != 0 OR school_div = 'S' ORDER BY school_div, school_name";
				foreach ($conn->query($schoolquery) as $school)
				{?>
					<h2 style="margin-bottom: 2px;padding-bottom: 2px;"><?php print $school['school_name']; ?></h2>
					<h2 style="font-size: .7em;margin-top: 2px;padding-bottom: 2px;">
						<?php print "(F".$school['F_quota']; ?>
						<?php print "/W".$school['W_quota']; ?>
						<?php print "/S".$school['S_quota'].")"; ?>
					</h2>
					
					<?php
					$stmt = $conn->prepare("SELECT * FROM schedule_events
											LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id
											LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
											WHERE visit_confirm = 0 AND school_id = :school_id
											ORDER BY submit_date");
					$stmt -> bindValue(':school_id', $school['school_id']);
					$stmt->execute();
					$booking_results = $stmt->fetchAll();
					
					foreach ($booking_results as $booking)
					{
						if ($booking['event_date'] != "0000-00-00") {$bg = "#88b572";}
						else {$bg = "#FFF";}
						?>
						<table class="pure-table pure-table-border" width="90%">
							<tr>
								<th colspan=2
									<?php 
										if ($booking['event_date'] != "0000-00-00") 
										{print " style=\"background: #88b572;\"><button disabled class=\"book-button\">BOOKED ON ".strtoupper(date("M d, Y", strtotime($booking['event_date'])))."</button>";}
										else 
										{print "><button id=\"book-me-".$booking['event_id']."\" class=\"book-button\">BOOK</button>";}
									?>
								</th>
								<th
									<?php 
										if ($booking['event_date'] != "0000-00-00") 
										{print " style=\"background: #88b572;\"><button id=\"confirm-me-".$booking['visit_id']."\" class=\"confirm-button\">CONFIRM</button>";}
										else {print "><button id=\"confirm-me-".$booking['visit_id']."\" class=\"confirm-button\" style=\"color: #AAA;\" disabled>CONFIRM</button>";}
									?>
								</th>
							</tr>
							<tr>
								<td style="background: <?php print $bg; ?>;" width="15%"><?php print $booking['teacher_name']; ?></td>
								<td style="background: <?php print $bg; ?>;" width="10%"><?php print $booking['grade']; ?></td>
								<td style="background: <?php print $bg; ?>;" width="20%"><?php print $booking['program_name']; ?></td>
							</tr>
							<tr><td style="background: <?php print $bg; ?>;" colspan="3" style="font-size: .6em;"><?php print $booking['visit_notes']; ?></td></tr>
						</table>
						<?php
					} ?>
					<hr style="width: 75%;"/>
					<?php
				}?>	
			</div>
		</div>
	</div>
</section>
	
<?php include("sya-footer.php"); ?>

<script>
	$(document).ready(function() {
		
		$('#calendar').load('../calendar/cal-calendar.php?r=sya', function() {
			$('#schedule').load('../schedule/cal-schedule-sy.php', function() {
			});
			return false;
		});
		
		$('.confirm-button').click(function(e) {
			e.preventDefault();
			$("#working").show();
			id = $(this).attr("id");
			id = id.replace("confirm-me-", "");
			//alert(id); return false;
			$.ajax({
		        type: "POST",
		        url: "process-book-confirm.php",
		        data: {id: id},
		        success: function() {location.reload('true');}
			});
			return false;
		});	
		
		$('.book-button').click(function(e) {
			e.preventDefault();
			$("#working").show();
			id = $(this).attr("id");
			id = id.replace("book-me-", "");
			//alert(id); return false;
			$.ajax({
		        type: "POST",
		        url: "process-book-date.php",
		        data: {id: id},
		        success: function() {location.reload('true');}
			});
			return false;
		});
		
	});	
</script>