<!DOCTYPE html>
<head>
	<link href="reportprint.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../../../scripts/pure/pure-min.css">  
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>School Year Breakdown</title>
	
	<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	
	$date_start = "2015-09-01";
	$date_end = "2016-06-30";
	$stmt = $conn->prepare("SELECT * FROM staff ORDER BY staff_name_last, staff_name_first");
	$stmt->execute();						
	$staff_results = $stmt->fetchAll();
	$count = 0;
	?>

</head>
<body>
	<?php
	for ($i = 1; $i <= 2; $i++)
	{?>
		<h2>
		<?php
		if ($count == 0) {print "Year to Date";$count =1;}
		else {print "Full Year";$date_end = "2017-06-30";}
		?>
		</h2>
		<table width=100%>
			<tr>
				<th>Staff</th>
				<th>Total</th>
				<th>Visits</th>
				<th>Office</th>
				<th>Lieu</th>
				<th>Other</th>
			</tr>
			<?php
			foreach ($staff_results as $staff)
			{
				?>
				<tr>
					<td><?php print $staff['staff_name_last'].", ".$staff['staff_name_common']; ?></td>
						<?php					
						$total_days = 0;
						$visits = 0;
						$office = 0;
						$lieu = 0;
						$other = 0;
						$day_count = 0;
						$newdate = 0;
						$olddate = 0;

						$stmt = $conn -> prepare("SELECT * FROM staff_workdays
																		LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
																		WHERE event_date BETWEEN :event_date_start AND :event_date_end AND workday_staff_id = :staff_id");
						$stmt -> bindValue(':event_date_start', $date_start);
						$stmt -> bindValue(':event_date_end', $date_end);
						$stmt -> bindValue(':staff_id', $staff['staff_id']);
						$stmt -> execute();
						$workdays = $stmt -> fetchAll();

						foreach($workdays as $workday)
						{
							if ($workday['event_type'] == "V" or $workday['event_type'] == "T")
							{
								$newdate = $workday['event_date'];
								if ($newdate == $olddate)
								{
									$day_count = $day_count + $workday['workday_percentage'];
									if ($day_count > 1){$day_count = 1;}
								}
								else
								{
									$total_days = $total_days + $day_count;
									$day_count = $workday['workday_percentage'];

								}
								if ($workday['event_type'] == "V"){$visits = $visits + 1;}
								if ($workday['event_type'] == "T")
								{
									$stmt = $conn -> prepare("SELECT * FROM schedule_task
																					WHERE task_id = :task_id");
									$stmt -> bindValue(':task_id', $workday['event_type_id']);
									$stmt -> execute();
									$task = $stmt -> fetch(PDO::FETCH_ASSOC);

									if ($task['task_name_id'] == "31" OR $task['task_name_id'] == "37") {$office = $office + $workday['workday_percentage'];}
									elseif ($task['task_name_id'] == "36") {$lieu = $lieu + $workday['workday_percentage'];}
									else {$other = $other + $workday['workday_percentage'];}
								}
								$olddate = $newdate;
							}
						}
						?>
					<td><?php print $total_days; ?></td>
					<td><?php print $visits; ?></td>
					<td><?php print $office; ?></td>
					<td><?php print $lieu; ?></td>
					<td><?php print $other; ?></td>
				</tr>
			<?php
			}
			?>
		</table>
	<?php
	}
	?>
</body>
</html>
