<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Bus Return Times</title>
</head>
<body>
	<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	$sortinput = "";
	if (isset($_GET['sort'])) {$sortinput = $_GET['sort'];}
	
	$busquery = "SELECT * FROM schedule_events
								LEFT JOIN schedule_bus_run ON schedule_bus_run.bus_run_id = schedule_events.event_type_id
								LEFT JOIN ss_routes ON ss_routes.route_id = schedule_bus_run.bus_run_route
								WHERE bus_run_lcvi_expect != ''
								ORDER BY event_date";
	
	$bgcolor = "#FFF";
	$new_date = "";
	$old_date = "";
	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Bus Schedule</h1>
	<table width="100%" class="pure-table pure-table-bordered">
		<tr>
			<th>Date</th>
			<th>Program</th>
			<th>Route</th>
			<th>Expect Arrival Time</th>
			<th>Updated Arrival Time</th>
			<th>Notes</th>
		</tr>
			
		<?php						
		foreach ($conn->query($busquery)  as $bus)
	    { 
	    	$new_date = $bus['event_date'];
	    	if ($new_date != $old_date AND $sortinput == ""){
	    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
	    	}
	    	?>
			<tr style="background: <?php print $bgcolor; ?>">
				<td <?php if ($bus['event_date'] == date("Y-m-d")) {print "style=\"font-weight: bold;\"";} ?>><?php print $bus['event_date']; ?></td>
				<td <?php if ($bus['bus_run_date'] == date("Y-m-d")) {print "style=\"font-weight: bold;\"";} ?>><?php print $bus['bus_run_program']; ?></td>
				<td <?php if ($bus['bus_run_date'] == date("Y-m-d")) {print "style=\"font-weight: bold;\"";} ?>><?php print $bus['route_description']; ?></td>
				<td <?php if ($bus['bus_run_date'] == date("Y-m-d")) {print "style=\"font-weight: bold;\"";} ?>><?php print date('g:i A', strtotime($bus['bus_run_departure_time'])); ?></td>
				<td style="text-align: center;<?php if ($bus['bus_run_date'] == date("Y-m-d")) {print "font-weight: bold;\"";} ?>"><?php print $bus['bus_run_lcvi_expect']; ?></td>
				<td style="white-space: pre-wrap;<?php if ($bus['bus_run_date'] == date("Y-m-d")) {print "font-weight: bold;\"";} ?>"><?php print $bus['bus_run_lcvi_notes']; ?></td>
			</tr>
			<?php					
			$old_date = $new_date;
		}
	?>
	</table>
</body>
</html>
