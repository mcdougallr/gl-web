<?php
$events = getevents("B", $_SESSION['date'], $conn);

foreach ($events as $event)
{
	$stmt = $conn->prepare("SELECT * FROM schedule_bus_run 
							LEFT JOIN ss_routes ON ss_routes.route_id = schedule_bus_run.bus_run_route
							WHERE bus_run_id = :bus_run_id");
	$stmt->bindValue(':bus_run_id', $event['event_type_id']);
	$stmt->execute();
	$bus_run = $stmt->fetch(PDO::FETCH_ASSOC);
	?>
	<div id="b_edit_<?php print $event['event_id']; ?>" class="pure-g schedule-item">
		<div class="pure-u-1-12 icon"><i style="color: #060;" class="fa fa-bus"></i></div>
		<div class="pure-u-11-12">
			<div class="details">
				<div class="title"><?php print $bus_run['route_description'];?></div>
				<div class="title"><?php print $bus_run['bus_run_program']; ?></div>
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
		</div>
	</div>
<?php 
$eventcount++;
}
