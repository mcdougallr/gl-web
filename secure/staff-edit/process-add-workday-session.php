<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (isset($_POST['val'])) {$addsection = cleantext($_POST['val']);}

if (isset($_POST['staff'])) {$staff = cleantext($_POST['staff']);}

if (!isset($addsection) OR !isset($staff)) {exit();}
			
$stmt = $conn -> prepare("SELECT event_id, summer_percentage FROM schedule_events
							LEFT JOIN schedule_summer ON schedule_summer.summer_id = schedule_events.event_type_id
							WHERE summer_section_id = :summer_section_id AND event_type = 'X'");
$stmt -> bindValue(':summer_section_id', $addsection);
$stmt -> execute();
$workdays = $stmt -> fetchAll();

foreach ($workdays as $workday) {  	
	$stmt = $conn -> prepare("INSERT INTO staff_workdays (workday_staff_id,workday_percentage,workday_event_id) 
								VALUES	(:workday_staff_id,:workday_percentage,:workday_event_id)");
	$stmt -> bindValue(':workday_staff_id', $staff);
	$stmt -> bindValue(':workday_percentage', $workday['summer_percentage']);
	$stmt -> bindValue(':workday_event_id', $workday['event_id']);
	
	$stmt -> execute();
  	
}