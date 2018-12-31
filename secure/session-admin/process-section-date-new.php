<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$summer_section_id = cleantext($_POST['summer_section_id']);		
$event_date = cleantext($_POST['event_date']);
$summer_description = cleantext($_POST['summer_description']);
$summer_percentage = cleantext($_POST['summer_percentage']);
$summer_notes = cleantext($_POST['summer_notes']);

$stmt = $conn -> prepare("INSERT INTO schedule_summer
							(summer_description,summer_percentage,summer_section_id,summer_notes)
							VALUES 
							(:summer_description,:summer_percentage,:summer_section_id,:summer_notes)");

$stmt -> bindValue(':summer_description', $summer_description);
$stmt -> bindValue(':summer_percentage', $summer_percentage);
$stmt -> bindValue(':summer_section_id', $summer_section_id);
$stmt -> bindValue(':summer_notes', $summer_notes);
$stmt->execute();
$summer_id = $conn -> lastInsertId();

$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
$stmt->bindValue(':event_date', $event_date);
$stmt->bindValue(':event_type', "X");
$stmt->bindValue(':event_type_id', $summer_id);
$stmt->execute();

header("Location: ses-section-edit.php?sid=".$summer_section_id);
?>