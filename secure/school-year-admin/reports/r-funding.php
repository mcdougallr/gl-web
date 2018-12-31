<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
	
	$year = date('Y');
	$start = $year - 1;
	$end = $year;
	
	if (isset($_GET['yearstart'])) {$start = cleantext($_GET['yearstart']);}
	if (isset($_GET['yearend'])) {$end = cleantext($_GET['yearend']);}
	
	$startdate = $start."-09-01";
	$enddate = $end."-06-30";
		
	//print $startdate;
	//print $enddate;
		
	$stmt = $conn->prepare("SELECT * FROM schedule_events
							LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id
							LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
							LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
							WHERE school_div = 'E' AND event_date BETWEEN :startdate AND :enddate AND event_type = 'V'
							ORDER BY school_name, event_date");
	$stmt->bindValue(':startdate', $startdate);
	$stmt->bindValue(':enddate', $enddate);
	$stmt->execute();
	$e_booking_results = $stmt->fetchAll();
	
	$e_total_classes = 0;
	$e_total_participants = 0;	
	
	foreach ($e_booking_results as $booking)
	{
		if ($booking['program_name'] != "Cancelled" and $booking['program_name'] != "Special (0)" and $booking['program_name'] != "Slideshow")
		{
			if ($booking['student_num'] != 0) {$participants = $booking['student_num'];}
			else {$participants = 24;}
			$e_total_classes++;
			$e_total_participants = $e_total_participants + $participants;
			print $booking['school_name']." - ".$booking['event_date'].": ".$booking['program_name']." - ".$participants." = ".$e_total_participants."<br />";
		}
	}
		
	$stmt = $conn->prepare("SELECT * FROM schedule_events
							LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id
							LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
							LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
							WHERE school_div != 'E' AND event_date BETWEEN :startdate AND :enddate AND event_type = 'V'
							ORDER BY school_name, event_date");
	$stmt->bindValue(':startdate', $startdate);
	$stmt->bindValue(':enddate', $enddate);
	$stmt->execute();
	$s_booking_results = $stmt->fetchAll();
	
	$s_total_classes = 0;
	$s_total_participants = 0;	
	
	foreach ($s_booking_results as $booking)
	{
		if ($booking['program_name'] != "Cancelled" and $booking['program_name'] != "Special (0)" and $booking['program_name'] != "Slideshow")
		{
			if ($booking['student_num'] != 0) {$participants = $booking['student_num'];}
			else {$participants = 24;}
			$s_total_classes++;
			$s_total_participants = $s_total_participants + $participants;
			print $booking['school_name']." - ".$booking['event_date'].": ".$booking['program_name']." - ".$participants." = ".$s_total_participants."<br />";
		}
	}	
		
		
?>

	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
		<h3><?php print $start."/".$end; ?> Elementary Total Class Visits: <?php print $e_total_classes; ?></h3>
        <h3><?php print $start."/".$end; ?> Total Particpants: <?php print $e_total_participants; ?></h3>
        <h3>---</h3>
        <h3><?php print $start."/".$end; ?> Secondary Total Class Visits: <?php print $s_total_classes; ?></h3>
		<h3><?php print $start."/".$end; ?> Total Particpants: <?php print $s_total_participants; ?></h3>
	</div>
</body>
</html>
