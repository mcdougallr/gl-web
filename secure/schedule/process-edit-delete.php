<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$type = cleantext($_POST['type']);
$type_day_id = cleantext($_POST['type_day_id']);
$event_id = cleantext($_POST['event_id']);

$table = "schedule_".$type;
$id_name = $type."_id";

$stmt = $conn -> prepare("DELETE FROM {$table} WHERE {$id_name} = :id_name_data");
$stmt -> bindValue(':id_name_data', $type_day_id);
$stmt -> execute();

$stmt = $conn -> prepare("DELETE FROM schedule_events WHERE event_id = :event_id");
$stmt -> bindValue(':event_id', $event_id);
$stmt -> execute();

if ($type != "bus_runs" OR $type != "info")
{
	$stmt = $conn -> prepare("DELETE FROM staff_workdays WHERE workday_event_id = :workday_event_id");
	$stmt -> bindValue(':workday_event_id', $event_id);
	$stmt -> execute();
}
?>
