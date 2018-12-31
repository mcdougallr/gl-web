<?php

if (isset($staff_id)) {$events = getstaffevents("T", $staff_id, $_SESSION['date'], $conn);}
else {$events = getevents("T", $_SESSION['date'], $conn);}

foreach ($events as $event)
{
	$stmt = $conn->prepare("SELECT * FROM schedule_task 
							LEFT JOIN sy_tasks ON sy_tasks.task_name_id = schedule_task.task_name_id
							WHERE task_id = :task_id");
	$stmt->bindValue(':task_id', $event['event_type_id']);
	$stmt->execute();
	$task = $stmt->fetch(PDO::FETCH_ASSOC);
	?>
	<div id="t_edit_<?php print $event['event_id']; ?>" class="pure-g schedule-item">
		<div class="pure-u-1-12 icon"><i style="color: #069;" class="fa fa-bug"></i></div>
		<div class="pure-u-11-12">
			<div class="details">
				<div class="title"><?php print $task['task_name']; ?></div>
				<?php 
				if ($task['task_notes'] != ""){print "<div class=\"notes\">".$task['task_notes']."</div>";}
				
				$staffcount = 0;
				$staffresult = getstafflist($event['event_id'], $conn);
				$staffcount = count($staffresult);
				
				if ($staffcount > 0)
				{
					print "<div style=\"color: #222;\" class=\"info\"><i style=\"color: #069;margin: 2px 5px 2px 0;\" class=\"fa fa-user\"></i>";
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
		</div>
	</div>
	<?php
	$eventcount++;
}

?>