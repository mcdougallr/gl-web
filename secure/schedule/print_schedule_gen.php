<?php

foreach ($gen_types as $gen_type)
{
	if (isset($staff_id)) {$events = getstaffevents($gen_type, $staff_id, $_SESSION['date'], $conn);}
	else {$events = getevents($gen_type, $_SESSION['date'], $conn);}

	foreach ($events as $event)
    {
    	if ($gen_type == "S") {$type_name = "supervision";$tcolor = "FF3338";$icon = "eye";}
		elseif ($gen_type == "O") {$type_name = "office";$tcolor = "66C";$icon = "desktop";}
		elseif ($gen_type == "E") {$type_name = "eq";$tcolor = "999";$icon = "cog";}
		elseif ($gen_type == "F") {$type_name = "food";$tcolor = "76BC83";$icon = "cutlery";}
		
		$table = "schedule_".$type_name;
		$type_id = $type_name."_id";
		$type_title = $type_name."_title";
		$type_notes = $type_name."_notes";
		
    	$stmt = $conn->prepare("SELECT * FROM {$table} WHERE {$type_id} = :type_id");
		$stmt->bindValue(':type_id', $event['event_type_id']);
		$stmt->execute();
		$day = $stmt->fetch(PDO::FETCH_ASSOC);
		?>

		<div id="g_edit_<?php print $event['event_id']; ?>" class="pure-g schedule-item">
			<div class="pure-u-1-12 icon"><i style="color: #<?php print $tcolor;?>;" class="fa fa-<?php print $icon;?>"></i></div>
			<div class="pure-u-11-12">
				<div class="details">
					<div class="title"><?php print $day[$type_title]; ?></div>
					<?php
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
					<div class="notes"><?php print $day[$type_notes];?></div>
				</div>
			</div>
		</div>
		<?php
		$eventcount++;
	}
}