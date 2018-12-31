<?php

$events = getevents("I", $_SESSION['date'], $conn);
foreach ($events as $event)
{
	$stmt = $conn->prepare("SELECT * FROM schedule_info WHERE info_id = :info_id");
	$stmt->bindValue(':info_id', $event['event_type_id']);
	$stmt->execute();
	$info = $stmt->fetch(PDO::FETCH_ASSOC);
	?>
	<div id="i_edit_<?php print $event['event_id']; ?>" class="pure-g schedule-item">
		<div class="pure-u-1-12 icon"><i style="color: #006;" class="fa fa-info-circle"></i></div>
		<div class="pure-u-11-12">
			<div class="details">
				<div class="title"><?php print $info['info_title']; ?></div>
				<div class="notes"><?php print $info['info_notes'];?></div>
			</div>
		</div>
	</div>
	<?php
	$eventcount++;
}
?>