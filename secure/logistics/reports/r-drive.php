<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Drive Schedule</title>
</head>
<body>
	<?php
	session_start();
	$year = date("Y");
	$start_date = $year."-01-01";
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	include ('../../shared/eventfunctions.php');

	$sortinput = "";
	if (isset($_GET['sort'])) {$sortinput = $_GET['sort'];}
	
	if ($sortinput == "route") {$sort = "route_depart, event_date";}
	else if ($sortinput == "truck") {$sort = "truck_name, event_date";}
	else if ($sortinput == "trailer") {$sort = "trailer_name, event_date";}
	else {$sort = "event_date";}

	
	$drivequery = "SELECT * FROM schedule_events
							LEFT JOIN schedule_drive ON schedule_drive.drive_id = schedule_events.event_type_id
							LEFT JOIN gl_vehicles ON gl_vehicles.vehicle_id = schedule_drive.drive_vehicle
							LEFT JOIN gl_trailers ON gl_trailers.trailer_id = schedule_drive.drive_trailer
							WHERE schedule_events.event_date > '$start_date' AND schedule_events.event_type = 'D'
							ORDER BY {$sort}";
	
	$bgcolor = "#FFF";
	$new_date = "";
	$old_date = "";
	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Drive Schedule</h1>
	<table width="100%" class="pure-table pure-table-bordered">
		<tr>
			<th><a class="sort_click" href="r-drive.php">Date <i class="fa fa-repeat"></i></a></th>
			<th><a class="sort_click" href="r-drive.php?sort=vehicle">Vehicle <i class="fa fa-repeat"></i></a></th>
			<th><a class="sort_click" href="r-drive.php?sort=trailer">Trailer <i class="fa fa-repeat"></i></a></th>
			<th>Duty</th>
			<th>Staff</th>
			<th>Note</th>
		</tr>
			
		<?php
		foreach ($conn->query($drivequery)  as $drive)
	    { 
	    	$new_date = $drive['event_date'];
	    	if ($new_date != $old_date AND $sortinput == ""){
	    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
	    	}?>
			<tr style="background: <?php print $bgcolor; ?>">
				<td><?php print $drive['event_date']; ?></td>
				<td><?php print $drive['vehicle_name']; ?></td>
				<td><?php if ($drive['trailer_id'] == "") {print "None";} else {print $drive['trailer_name'];} ?></td>
				<td><?php print $drive['drive_duty']; ?></td>
				<td>
					<?php
					$count = 0;
					$stafflist = getstafflist($drive['event_id'],$conn);
					
					foreach ($stafflist as $staff)
					{
						if ($count != 0) {print ", ";}	
						print $staff['staff_name_common']." ".$staff['staff_name_last'];
						$count++;
					}
					?>
				</td>
				<td style="white-space: pre-wrap;"><?php print $drive['drive_notes']; ?></td>
			</tr>
			<?php
			$old_date = $new_date;					
		}
	?>
	</table>
</body>
</html>
