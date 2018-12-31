<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$staff_id = cleantext($_POST['staff']);

$stmt = $conn -> prepare("DELETE staff_workdays FROM staff_workdays 
						LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
						WHERE workday_staff_id = :workday_staff_id AND event_type = :event_type");
$stmt -> bindValue(':workday_staff_id', $staff_id);
$stmt -> bindValue(':event_type', "X");
$stmt -> execute();