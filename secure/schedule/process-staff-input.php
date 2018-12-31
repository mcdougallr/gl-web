<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$event_id = cleantext($_POST['workday_event_id']);
$staff_id = cleantext($_POST['workday_staff_id']); 
$percent = cleantext($_POST['workday_percentage']);

$stmt = $conn -> prepare("INSERT INTO staff_workdays
							(workday_staff_id, workday_percentage, workday_event_id) 
							VALUES
							(:workday_staff_id, :workday_percentage, :workday_event_id)");

$stmt -> bindValue(':workday_staff_id', $staff_id);
$stmt -> bindValue(':workday_percentage', $percent);
$stmt -> bindValue(':workday_event_id', $event_id);
$stmt -> execute();

?>
