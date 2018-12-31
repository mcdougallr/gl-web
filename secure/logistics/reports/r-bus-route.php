<?php session_start(); 
$year = date("Y");
$start_date = $year."-01-01";
?>
<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Bus Schedule by Route</title>
</head>
<body>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Bus Schedule by Route</h1>
	<table width="100%" class="pure-table pure-table-bordered">
		<tr>
			<th>Date</th>
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
								ORDER BY route_sort, event_date, bus_run_departure_time";
		
		$bgcolor = "#FFF";
		$new_route = "";
		$old_route = "";
		$route_count = array();
		$group_count = array();
		
		foreach ($conn->query($busquery)  as $bus)
	    { 
	    	$new_route = $bus['route_description'];
	    	if ($new_route != $old_route){
	    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
				$route_id = $bus['route_id'];
				$route_count[$route_id] = 0;
	    	}
			if ($bus['bus_run_return_time'] == NULL) {$runs = $bus['bus_run_number'];}
			else {$runs = $bus['bus_run_number'] * 2;}
			$route_count[$route_id] = $route_count[$route_id] + $runs;
			
			$group_number = $bus['route_group'];
			if (!isset($group_count[$group_number])) {$group_count[$group_number] = 0;}
			$group_count[$group_number] = $group_count[$group_number] + $runs;
			
	    	?>
			<tr style="background: <?php print $bgcolor; ?>">
				<td><?php print $bus['event_date']; ?></td>
				<td><?php print date('g:i A', strtotime($bus['bus_run_departure_time'])); ?></td>
				<td style="text-align: center;"><?php print $bus['bus_run_number']; ?></td>
				<td style="text-align: center;"><?php if ($bus['bus_run_carrier'] == 1) {print "Y";} else {print "N";} ?></td>
				<td><?php print $bus['route_description']; ?></td>
				<td><?php if ($bus['bus_run_return_time'] == NULL) {print "None";} else {print date('g:i A', strtotime($bus['bus_run_return_time']));} ?></td>
				<td><?php print $bus['bus_run_program']; ?></td>
				<td style="white-space: pre-wrap;"><?php print $bus['bus_run_notes']; ?></td>
			</tr>
			<?php					
			$old_route = $new_route;
		}
		?>
	</table>
	<br />
	<div class="pagebreak">
		<table width="100%" class="pure-table pure-table-bordered">
			<tr>
				<th>Route Description</th>
				<th>Count</th>
				<th>Group Count</th>
				<th>Distance (km)</th>
				<th>Total Distance (km)</th>
				<th>Time (hr)</th>
				<th>Total Time (hr)</th>
				<th>Cost ($)</th>
				<th>Total Cost ($)</th>
			</tr>
			<?php
			$routequery = "SELECT * FROM ss_routes WHERE route_hide != 1 ORDER BY route_group, route_sort";
			
			$bgcolor = "#FFF";
			$new_route = "";
			$old_route = "";
			$new_group = "";
			$old_group = "";
			$totaldistance = 0;
			$totaltime = 0;
			$totalcost = 0;
			$first = 0;
			
			foreach ($conn->query($routequery)  as $route)
		    { 
				$new_route = $route['route_id'];
				if ($new_route != $old_route){
					$route_id = $route['route_id'];
					if (!isset($route_count[$route_id])) {$route_count[$route_id] = 0;}
		    	}
				
				$new_group = $route['route_group'];
				if ($new_group != $old_group){
		    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
					else {$bgcolor = "#F2F2F2";}
					$first = 0;
		    	}
								
				$distance = $route_count[$route_id] * $route['route_distance'];
				$totaldistance = $totaldistance + $distance;
				$time = $route_count[$route_id] * $route['route_time'];
				$totaltime = $totaltime + $time;
				$cost = $route_count[$route_id] * $route['route_cost'];
				$totalcost = $totalcost + $cost;
				
		    	?>
				<tr style="background: <?php print $bgcolor; ?>">
					<td><?php print $route['route_description']; ?></td>
					<td><?php print $route_count[$route_id]; ?></td>
					<?php
					if ($first == 0) {
						$stmt = $conn->prepare("SELECT route_id FROM ss_routes WHERE route_group = :route_group AND route_hide != 1");
						$stmt->bindValue(':route_group', $new_group);
						$stmt->execute();
						$grouprowcount = $stmt->fetchAll();
						if (!isset($group_count[$new_group])) {$group_count[$new_group] = 0;}
						print "<td rowspan=\"".count($grouprowcount)."\">".$group_count[$new_group]."</td>";
						$first = 1;
					} ?>
					<td><?php print $route['route_distance']; ?></td>
					<td><?php print  $distance; ?></td>
					<td><?php print $route['route_time']; ?></td>
					<td><?php print $time; ?></td>
					<td><?php print $route['route_cost']; ?></td>
					<td><?php print  $cost; ?></td>
				</tr>
				<?php					
				$old_route = $new_route;
				$old_group = $new_group;
			}
			?>
			<tr style="background: #999;color: #222;font-weight: bold;">
				<td colspan="3">Totals</td>
				<td colspan="2" style="text-align: center;"><?php print $totaldistance; ?></td>
				<td colspan="2" style="text-align: center;"><?php print $totaltime; ?></td>
				<td colspan="2" style="text-align: center;"><?php print $totalcost; ?></td>
			</tr>
		</table>
	</div>
</body>
</html>
