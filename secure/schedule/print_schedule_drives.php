<?php
if (isset($staff_id)) {$events = getstaffevents("D", $staff_id, $_SESSION['date'], $conn);}
else {$events = getevents("D", $_SESSION['date'], $conn);}

foreach ($events as $event)
{
	$stmt = $conn->prepare("SELECT * FROM schedule_drive WHERE drive_id = :drive_id");
	$stmt->bindValue(':drive_id', $event['event_type_id']);
	$stmt->execute();
	$drive = $stmt->fetch(PDO::FETCH_ASSOC);
	
	?>
	<div id="d_edit_<?php print $event['event_id']; ?>" class="pure-g schedule-item">
		<div class="pure-u-1-12 icon"><i style="color: #222;" class="fa fa-road"></i></div>
		<div class="pure-u-11-12">
			<div class="details">
				<?php
					if ($drive['drive_duty'] != "")
					{
						print "<div class=\"title\">".$drive['drive_duty']."</div>";
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
						print "<div class=\"info\"><i style=\"color: #069;margin: 2px 5px 2px 0;\" class=\"fa fa-user\"></i>";
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
		</div>
	</div>
	<?php
	$eventcount++;
}