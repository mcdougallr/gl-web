<!DOCTYPE html>
<head>
	<link href="reportprint.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../../../scripts/pure/pure-min.css">  
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>School Year Staff Classes</title>
	
	<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	
	$start_day = $_SESSION['date'];
	$end_day = date('Y-m-d', strtotime("+3 months", strtotime($start_day)));
	
	$stmt = $conn->prepare("SELECT * FROM staff
							WHERE staff_access > 1
							ORDER BY staff_name_last, staff_name_first");
	$stmt->execute();						
	$staff_results = $stmt->fetchAll();
	?>
	
<style>
	body {
		font-family: Times;
	}
	h1 {
		font-family: Times;
		font-size: 1.2em;
		font-weight: normal;
		margin: 5px;
	}
	h2 {
		font-family: Times;
		font-size: 1em;
		font-weight: normal;
		margin: 5px;
	}
	th, td {
		font-family: Times;
		font-size: 1em;	
		color: #000;
	}
</style>

</head>
<body>
	<?php
	foreach ($staff_results as $staff)
	{
		$stmt = $conn -> prepare("SELECT event_date,  school_name, program_name FROM staff_workdays
									LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
									LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id
									LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
						LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
									WHERE event_date BETWEEN :event_date_start AND :event_date_end AND workday_staff_id = :staff_id AND event_type = 'V'");
        $stmt -> bindValue(':event_date_start', $start_day);
        $stmt -> bindValue(':event_date_end', $end_day);
        $stmt -> bindValue(':staff_id', $staff['staff_id']);
        $stmt -> execute();
        $workdays = $stmt -> fetchAll();
		
	
			?>
			<div class="pagebreak">
				<br>
				<h1><?php print $staff['staff_name_common']." ".$staff['staff_name_last'];?></h1>
					<div style="text-align: center;">
		<table style="margin: 0 auto;width: 90%;">
			<tr>
				<th style="text-align: left;">Date</th>
				<th>School</th>
				<th>Program</th>
			</tr>
	  	<?php 

	  	foreach ($workdays as $workday)
		{?>
			 <tr >
			 	<td width="15%" style="text-align: left;"><?php print $workday['event_date']; ?></td>
			 	<td width="15%"><?php print $workday['school_name']; ?></td>
			 	<td width="10%"><?php print $workday['program_name']; ?></td>
			 </tr>
			 <?php 
			 $count++;
			 $old = $new;
		} ?>
	 	</table>
						<br>
	</div>
			</div>
	<?php	
	}
	?>

</body>
</html>
