<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$debrief_notes = cleantext($_POST['field_val']);
$trip_id = cleantext($_POST['trip_id']);
$debrief_id = cleantext($_POST['debrief_id']);

if ($debrief_id == "0")
{
	$stmt = $conn -> prepare("INSERT INTO fp_debriefs
													(debrief_trip_id, debrief_notes) 
													VALUES
													(:debrief_trip_id, :debrief_notes)");
	$stmt -> bindValue(":debrief_trip_id", $trip_id);
	$stmt -> bindValue(":debrief_notes", $debrief_notes);
	$stmt -> execute();
}
else
{
	$stmt = $conn -> prepare("UPDATE fp_debriefs SET debrief_notes = :debrief_notes WHERE debrief_trip_id = :debrief_trip_id");
	$stmt -> bindValue(":debrief_notes", $debrief_notes);
	$stmt -> bindValue(":debrief_trip_id", $trip_id);
	$stmt -> execute();
}
