<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$event_id = cleantext($_POST['event_id']);

$stmt = $conn->prepare("SELECT * FROM schedule_events WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();				
$event = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn -> prepare("DELETE FROM schedule_events WHERE event_id = :event_id");
$stmt -> bindValue(':event_id', $event_id);
$stmt -> execute();

$stmt = $conn -> prepare("DELETE FROM schedule_summer WHERE summer_id = :summer_id");
$stmt -> bindValue(':summer_id', $event['event_type_id']);
$stmt -> execute();

$stmt = $conn -> prepare("DELETE FROM staff_workdays WHERE workday_event_id = :workday_event_id");
$stmt -> bindValue(':workday_event_id', $event_id);
$stmt -> execute();