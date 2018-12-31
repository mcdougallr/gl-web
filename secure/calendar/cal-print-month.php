<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/functions.php');
include ('../shared/eventfunctions.php');

?>

<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="_cal-print-month.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Overlock:400,400italic' rel='stylesheet' type='text/css'>
		<title>Gould Lake Calendar</title>
	</head>
	<body>
		<?php include ('../shared/authenticate.php');	

	//DATE INFORMATION	
	$date = $_SESSION['date'];
	$year = date("Y",strtotime(($date)));
	$month = date("m",strtotime(($date)));
	$firstday = $year."-".$month."-01";
	$month_begin = date("w",strtotime(($firstday)));
  	$month_length = date("t",strtotime($date));	

	//INCOMING DATA
	if (isset($_GET['r'])) {$refer = $_GET['r'];}
	else {header("Location ../staff/index.php");}
	if (isset($_GET['sid'])) {$staff_id = $_GET['sid'];}
	
	if ($refer == "s" AND isset($staff_id) OR $refer == "sa" AND isset($staff_id)) 
	{
		$stmt = $conn->prepare("SELECT staff_id, staff_name_common FROM staff WHERE staff_id = :staff_id");
	   	$stmt->bindValue(':staff_id', $staff_id);
		$stmt->execute();
	   	$staff = $stmt -> fetch(PDO::FETCH_ASSOC);
		$title = $staff['staff_name_common']."'s - ".date("F",strtotime($date));
	}
	elseif ($refer == "log" AND isset($_GET['logt'])) {$log_type = $_GET['logt']; $title = "Logistics - ".date("F",strtotime($date));}
	elseif ($refer == "sya" OR $refer == "sy") {{$title = "School Year - ".date("F",strtotime($date));}}
	else {header("Location ../staff/index.php");}

	//DATE INFORMATION	
	$date = $_SESSION['date'];
	$year = date("Y",strtotime(($date)));
	$month = date("m",strtotime(($date)));
	$firstday = $year."-".$month."-01";
	$month_begin = date("w",strtotime(($firstday)));
  	$month_length = date("t",strtotime($date));	
	
	?>
		
	<div id="staffschedule">
		<table>
		<tr><th colspan=7 align=center height=25 style="font-family: FTYS;font-size: 1.5em;font-weight: normal;"><?php print $title; ?></th></tr>
		<tr>
			<th style="background: #AAA;color: #222;">Sunday</th>
			<th style="background: #AAA;color: #222;">Monday</th>
			<th style="background: #AAA;color: #222;">Tuesday</th>
			<th style="background: #AAA;color: #222;">Wednesday</th>
			<th style="background: #AAA;color: #222;">Thursday</th>
			<th style="background: #AAA;color: #222;">Friday</th>
			<th style="background: #AAA;color: #222;">Saturday</th>
		</tr>
	  	<?php
	  	$z=0;
		for($i= 1 - $month_begin; $i<$month_length+1; $i++)
		{
			if ($z == 0) {print "<tr>";}
	    	if ($i < 1) {print "<td bgcolor=\"#CCC\">&nbsp;</td>";}
	    	else
	  		{
      			$bgcolor="#FFFFFF";
      			$calendardate = $year."-".$month."-".$i;
		      	?>
		      	<td>
			      	<div class='calendardate'><?php print $i; ?></div>
			      	
			      	<?php
			      	// INFO
					
				
				if ($refer == "sa" OR $refer == "sy" OR $refer == "sya" OR $refer == "log") 
			      	{$events = getevents("I", $calendardate, $conn);

						foreach ($events as $event)
						{
							$stmt = $conn->prepare("SELECT * FROM schedule_info WHERE info_id = :info_id");
							$stmt->bindValue(':info_id', $event['event_type_id']);
							$stmt->execute();
							$info = $stmt->fetch(PDO::FETCH_ASSOC);
							?>
							<div class="details">
								<div class="title"><i style="color: #006;" class="fa fa-info-circle"></i><?php print $info['info_title']; ?></div>
								<div class="info"><?php print $info['info_notes'];?></div>
							</div>
							<?php
						}
					}

			      	// SESSION WORKDAYS
			      	if ($refer == "s" OR $refer == "sa") 
			      	{
				      	$events = getstaffevents("X", $staff_id, $calendardate, $conn);
						foreach ($events as $event)
						{
							$stmt = $conn->prepare("SELECT session_program_code, session_number, summer_description, summer_notes, summer_percentage FROM schedule_summer  
													LEFT JOIN ss_session_sections ON ss_session_sections.section_id = schedule_summer.summer_section_id
													LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_session_sections.section_session_id
													WHERE summer_id = :summer_id");
							$stmt->bindValue(':summer_id', $event['event_type_id']);
							$stmt->execute();
							$session_day = $stmt->fetch(PDO::FETCH_ASSOC);
							?>
							<div class="details">
								<div class="title"><i style="color: #093;" class="fa fa-tree"></i>
								<?php print $session_day['session_program_code'] . $session_day['session_number']." (".$session_day['summer_percentage'].")"; ?></div>
								<?php 
								if ($session_day['summer_description']!="")
								{?>
									<div class="info"><?php print $session_day['summer_description']; ?></div>
								<?php 
								}
								if ($session_day['summer_notes']!="")
								{?>
									<div class="notes"><?php print $session_day['summer_notes']; ?></div><br />
								<?php
								}?>
      						<?php
						}
	      			}
					
					// SCHOOL YEAR VISITS
					if ($refer == "s" OR $refer == "sa" OR $refer == "sy" OR $refer == "sya")
					{							
		      			if ($refer == "s" OR $refer == "sa") {$events = getstaffevents("V", $staff_id, $calendardate, $conn);}
						else {$events = getevents("V", $calendardate, $conn);}
						foreach ($events as $event)
						{
							$stmt = $conn->prepare("SELECT * FROM schedule_visit  
													LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
													LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
													WHERE visit_id = :visit_id");
							$stmt->bindValue(':visit_id', $event['event_type_id']);
							$stmt->execute();
							$visit = $stmt->fetch(PDO::FETCH_ASSOC);
							?>
							<div class="details">
								<div class="title"><i style="color: #093;" class="fa fa-tree"></i>
								<?php 
									print $visit['program_name']; 
									if ($visit['early'] == "Y") {print " **";}
									?>
								</div>
								<?php 
								if ($visit['school_name'] !=""){print "<div class=\"info\">".$visit['school_name']."</div>";}
								if ($visit['teacher_name'] != "" OR $visit['grade'] != "" OR $visit['student_num'] != 0)
								{
									print "<div class=\"info\">(";
									if ($visit['teacher_name'] != ""){print $visit['teacher_name'];}
									if ($visit['grade'] != ""){print " - G. ".$visit['grade'];}
									if ($visit['student_num'] != 0){print " - #".$visit['student_num'];}
									print ")</div>"; 
								}
								if ($visit['visit_notes'] != ""){print "<div class=\"notes\">".$visit['visit_notes']."</div>";}
								
								$staffcount = 0;
								$staffresult = getstafflist($event['event_id'], $conn);
								$staffcount = count($staffresult);
								
								if ($staffcount > 0)
								{
									print "<div style=\"color: #222;\" class=\"info\"><i style=\"color: #069;margin: 2px 0;\" class=\"fa fa-user\"></i>";
									$stafftally = 0;
									foreach ($staffresult as $staff)
									{
										if ($stafftally !=0) {print ", ";}
										print $staff['staff_name_common']." ".$staff['staff_name_last'];
										$stafftally++;
									}
									print "</div>";
								}
								?>
							</div>
							<?php
						}
					}
						
					// SCHOOL YEAR TASKS
					if ($refer == "s" OR $refer == "sa" OR $refer == "sy" OR $refer == "sya")
					{							
		      			if ($refer == "s" OR $refer == "sa") {$events = getstaffevents("T", $staff_id, $calendardate, $conn);}
						else {$events = getevents("T", $calendardate, $conn);}
						foreach ($events as $event)
						{
							$stmt = $conn->prepare("SELECT * FROM schedule_task 
													LEFT JOIN sy_tasks ON sy_tasks.task_name_id = schedule_task.task_name_id
													WHERE task_id = :task_id");
							$stmt->bindValue(':task_id', $event['event_type_id']);
							$stmt->execute();
							$task = $stmt->fetch(PDO::FETCH_ASSOC);
							?>
							<div class="details">
								<div class="title"><i style="color: #069;" class="fa fa-bug"></i><?php print $task['task_name']; ?></div>
								<?php 
								if ($task['task_notes'] != ""){print "<div class=\"notes\">".$task['task_notes']."</div>";}
								
								$staffcount = 0;
								$staffresult = getstafflist($event['event_id'], $conn);
								$staffcount = count($staffresult);
								
								if ($staffcount > 0)
								{
									print "<div style=\"color: #222;\" class=\"info\"><i style=\"color: #069;margin: 2px 0;\" class=\"fa fa-user\"></i>";
									$stafftally = 0;
									foreach ($staffresult as $staff)
									{
										if ($stafftally !=0) {print ", ";}
										print $staff['staff_name_common']." ".$staff['staff_name_last'];
										$stafftally++;
									}
									print "</div>";
								}
								?>
							</div>
							<?php
						}
		      		}
					
					// BUS
					if ($refer == "log" AND $log_type == "b" OR $refer == "log" AND $log_type == "all")
					{							
		      			$events = getevents("B", $calendardate, $conn);
						foreach ($events as $event)
						{
							$stmt = $conn->prepare("SELECT * FROM schedule_bus_run 
													LEFT JOIN ss_routes ON ss_routes.route_id = schedule_bus_run.bus_run_route
													WHERE bus_run_id = :bus_run_id");
							$stmt->bindValue(':bus_run_id', $event['event_type_id']);
							$stmt->execute();
							$bus_run = $stmt->fetch(PDO::FETCH_ASSOC);
							?>
							<div class="details">
								<div class="title"><i style="color: #060;" class="fa fa-bus"></i><?php print $bus_run['route_description'];?></div>
								<div class="title" style="margin-left: 15px;"><?php print $bus_run['bus_run_program']; ?></div>
								<div class="info">
									<?php print $bus_run['bus_run_number']." ";
								 	if ($bus_run['bus_run_carrier'] == 1) {print "Carrier";}
								 	else {print "Non-carrier";}
								 	if ($bus_run['bus_run_number'] > 1){print "s";}?>
								</div>
								<div class="info"><?php print "Depart: ".date('g:i A', strtotime($bus_run['bus_run_departure_time']));?></div>
								<div class="info">
									<?php print "Return: ";
									if ($bus_run['bus_run_return_time'] == NULL) {print "None";}
									else {print date('g:i A', strtotime($bus_run['bus_run_return_time']));} ?>
								</div>
								<div class="notes"><?php print $bus_run['bus_run_notes']; ?></div>
							</div>
						<?php 
						}
		      		}
					
					// DRIVE WORKDAYS
					if ($refer == "s" OR $refer == "sa" OR $refer == "log" AND $log_type == "d" OR $refer == "log" AND $log_type == "all")
					{							
		      			if ($refer == "s" OR $refer == "sa") {$events = getstaffevents("D", $staff_id, $calendardate, $conn);}
						else {$events = getevents("D", $calendardate, $conn);}

						foreach ($events as $event)
						{
							$stmt = $conn->prepare("SELECT * FROM schedule_drive WHERE drive_id = :drive_id");
							$stmt->bindValue(':drive_id', $event['event_type_id']);
							$stmt->execute();
							$drive = $stmt->fetch(PDO::FETCH_ASSOC);
							
							?>
							<div class="details">
								<div class="title"><i style="color: #222;" class="fa fa-road"></i>
								<?php
									if ($drive['drive_duty'] != "")
									{
										print $drive['drive_duty']."</div>";
									}
															
									if ($drive['drive_vehicle'] != 0)
									{
										$stmt = $conn->prepare("SELECT vehicle_name FROM gl_vehicles WHERE vehicle_id = :vehicle_id");
										$stmt->bindValue(':vehicle_id', $drive['drive_vehicle']);
										$stmt->execute();
										$vehicle = $stmt -> fetch(PDO::FETCH_ASSOC);
										print "<div class=\"info\">".$vehicle['vehicle_name'];
										if ($drive['drive_trailer'] == 0){print "</div>";}
									}
									else {
										print  "<div class=\"title\">No truck ";
									}
									
									if ($drive['drive_trailer'] != 0)
									{		
										$stmt = $conn->prepare("SELECT trailer_name FROM gl_trailers WHERE trailer_id = :trailer_id");
										$stmt->bindValue(':trailer_id', $drive['drive_trailer']);
										$stmt->execute();
										$trailer = $stmt -> fetch(PDO::FETCH_ASSOC);
										print " - ".$trailer['trailer_name'];
										print "</div>";
									}
								
									$staffcount = 0;
									
									$staffresult = getstafflist($event['event_id'], $conn);
									$staffcount = count($staffresult);
									
									if ($staffcount > 0)
									{
										print "<div style=\"color: #222;\" class=\"info\"><i style=\"color: #069;margin: 2px 0;\" class=\"fa fa-user\"></i>";
										$stafftally = 0;
										foreach ($staffresult as $staff)
										{
											if ($stafftally !=0) {print ", ";}
											print $staff['staff_name_common']." ".$staff['staff_name_last'];
											$stafftally++;
										}
										print "</div>";
									}
								?>
								<div class="notes"><?php print $drive['drive_notes'];?></div>
							</div>
							<?php
						}
					}
	
					// EQ
					if ($refer == "s" OR $refer == "sa" OR $refer == "log")
					{
		      			if ($refer == "s" OR $refer == "sa" OR $log_type == "all") {$gen_types = array("E","F","S","O");}	
						elseif ($log_type == "e") {$gen_types = array("E");}
						elseif ($log_type == "f") {$gen_types = array("F");}
						elseif ($log_type == "s") {$gen_types = array("S");}	
						elseif ($log_type == "o") {$gen_types = array("E");}
						else {$gen_types = array();}
						
						foreach($gen_types as $gentype)
						{	
		      				if ($refer == "s" OR $refer == "sa") {$events = getstaffevents($gentype, $staff_id, $calendardate, $conn);}
							else {$events = getevents($gentype, $calendardate, $conn);}
						
							foreach ($events as $event)
						    {
						    	if ($gentype == "S") {$type_name = "supervision";$tcolor = "FF3338";$icon = "eye";}
								elseif ($gentype == "O") {$type_name = "office";$tcolor = "66C";$icon = "desktop";}
								elseif ($gentype == "E") {$type_name = "eq";$tcolor = "999";$icon = "cog";}
								elseif ($gentype == "F") {$type_name = "food";$tcolor = "76BC83";$icon = "cutlery";}
								
								$table = "schedule_".$type_name;
								$type_id = $type_name."_id";
								$type_title = $type_name."_title";
								$type_notes = $type_name."_notes";
								
						    	$stmt = $conn->prepare("SELECT * FROM {$table} WHERE {$type_id} = :type_id");
								$stmt->bindValue(':type_id', $event['event_type_id']);
								$stmt->execute();
								$day = $stmt->fetch(PDO::FETCH_ASSOC);
								?>
						
								<div class="details">
									<div class="title"><i style="color: #<?php print $tcolor;?>;" class="fa fa-<?php print $icon;?>"></i><?php print $day[$type_title]; ?></div>
									<?php
										$staffcount = 0;
										$staffresult = getstafflist($event['event_id'], $conn);
										$staffcount = count($staffresult);
										
										if ($staffcount > 0)
										{
											print "<div style=\"color: #222;\" class=\"info\"><i style=\"color: #069;margin: 2px 0;\" class=\"fa fa-user\"></i>";
											$stafftally = 0;
											foreach ($staffresult as $staff)
											{
												if ($stafftally !=0) {print ", ";}
												print $staff['staff_name_common']." ".$staff['staff_name_last'];
												$stafftally++;
											}
											print "</div>";
										}
									?>
									<div class="notes"><?php print $day[$type_notes];?></div>
								</div>
								<?php
							}
						}
					}
					?>
	      		</td>
	      		<?php
    		}
    		$z++;
    		if ($z == 7)
    		{
    			print "</tr>";
    			$z=0;
			}
  		}
  		if ($z != 7 and $z != 0)
  		{
    	for ($i=0; $i<(7-$z); $i++)
    	{print "<td bgcolor=\"#CCC\"></td>";}
    	print "</tr>";
  	}
	print "</table><br />";
	if ($month==6) {print "</div>";}
	?>
</div>