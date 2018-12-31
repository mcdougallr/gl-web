<?php session_start(); 
$year = date("Y");
$start_date = $year."-01-01";
?>
	
<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Bus Schedule by Date</title>
</head>
<body>

	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Bus Schedule by Date</h1>
	<table width="100%" class="pure-table pure-table-bordered">
		<tr>
			<th>Date </th>
			<th>Time</th>
			<th style="text-align: center;">#</th>
			<th style="text-align: center;">Carrier</th>
			<th>Route</th>
			<th>Return</th>
			<th>Program</th>
			<th>Note</th>
		</tr>
			
		<?php		
		include ('../../shared/dbconnect.php');
		include ('../../shared/functions.php');
		include ('../../shared/authenticate.php');
	
		$busquery = "SELECT * FROM schedule_events
								LEFT JOIN schedule_bus_run ON schedule_bus_run.bus_run_id = schedule_events.event_type_id
								LEFT JOIN ss_routes ON ss_routes.route_id = schedule_bus_run.bus_run_route
								WHERE schedule_events.event_date > '$start_date' AND schedule_events.event_type = 'B'
								ORDER BY event_date, bus_run_departure_time";
		$bgcolor = "#FFF";
		$new_date = "";
		$old_date = "";
		foreach ($conn->query($busquery)  as $bus)
	    { 
	    	$new_date = $bus['event_date'];
	    	if ($new_date != $old_date){
	    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
	    	}
	    	?>
			<tr style="background: <?php print $bgcolor; ?>">
				<td width="10%" ><?php print $bus['event_date']; ?></td>
				<td width="10%" ><?php print date('g:i A', strtotime($bus['bus_run_departure_time'])); ?></td>
				<td width="5%" style="text-align: center;"><?php print $bus['bus_run_number']; ?></td>
				<td width="5%" style="text-align: center;"><?php if ($bus['bus_run_carrier'] == 1) {print "Y";} else {print "N";} ?></td>
				<td width="15%" ><?php print $bus['route_description']; ?></td>
				<td width="10%" ><?php if ($bus['bus_run_return_time'] == NULL) {print "None";} else {print date('g:i A', strtotime($bus['bus_run_return_time']));} ?></td>
				<td width="10%" ><?php print $bus['bus_run_program']; ?></td>
				<td width="35%" style="white-space: pre-wrap;"><?php print $bus['bus_run_notes']; ?></td>
			</tr>
			<?php					
			$old_date = $new_date;
		}
	?>
	</table>
</body>
</html>
