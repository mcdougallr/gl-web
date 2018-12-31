<?php

$events = getstaffevents("X", $staff_id, $_SESSION['date'], $conn);
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
	$eventcount++;
}
?>