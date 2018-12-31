<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$event_id = cleantext($_POST['event_id']);
	
$stmt = $conn->prepare("SELECT event_id FROM schedule_events WHERE event_id = :event_id");
$stmt->bindValue(':event_id', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);
	
$stmt = $conn -> prepare("DELETE FROM schedule_visit WHERE visit_id = :visit_id");
$stmt -> bindValue(':visit_id', $event['event_type_id']);
$stmt -> execute();

$stmt = $conn -> prepare("DELETE FROM schedule_events WHERE event_id = :event_id");
$stmt -> bindValue(':event_id', $event_id);
$stmt -> execute();
?>