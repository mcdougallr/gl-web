<!DOCTYPE html>
<head>
	<link href="reportprint.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../../../scripts/pure/pure-min.css">  
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>School Year Bookings</title>
	
	<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

?>
</head>
<body>
	<div style="text-align: center;">
		<h2 style="display: inline;">Current Unscheduled Classes</h2>
		<img src="sunman-black.png" width="30"/>
	</div>
	<div style="text-align: center;">
		<table style="margin: 0 auto;">
			<tr>
				<th style="text-align: left;">School</th>
				<th>Submit Date</th>
				<th>Teacher</th>
				<th>Grade</th>
				<th>Program</th>
				<th style="text-align: left;">Notes</th>
			</tr>
	  	<?php 
	  		
		$bookingquery = "SELECT * FROM schedule_events
						LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id
						LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
						LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
						WHERE event_date = '0000-00-00' AND event_type = 'V'
						ORDER BY school_name, submit_date";
	  	$new = "";
		$old = "";
		$last = "";
		$count = 0;
		$bgcolor = "#F2F2F2";
	  	foreach ($conn->query($bookingquery) as $booking)
		{
			$new = $booking['school_name'];
			if ($new != $old) {
				if ($bgcolor == "#F2F2F2") {$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
			}
			?>	
			 <tr style="background: <?php print $bgcolor; ?>">
			 	<td width="15%" style="text-align: left;"><?php print $booking['school_name']; ?></td>
			 	<td width="15%"><?php print $booking['submit_date']; ?></td>
			 	<td width="10%"><?php print $booking['teacher_name']; ?></td>
			 	<td width="5%"><?php print $booking['grade']; ?></td>
			 	<td width="15%"><?php print $booking['program_name']; ?></td>
			 	<td width="40%" style="text-align: left;"><?php print $booking['visit_notes']; ?></td>
			 </tr>
			 <?php 
			 $count++;
			 $old = $new;
		} ?>
	 	</table>
	</div>
</body>
</html>
