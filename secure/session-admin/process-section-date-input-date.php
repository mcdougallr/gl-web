<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$id = cleantext($_POST['event_id']);
$field_val = cleantext($_POST['field_val']);

$stmt = $conn -> prepare("UPDATE schedule_events SET event_date = :event_date WHERE event_id  = :event_id");
$stmt -> bindValue(':event_date', $field_val);
$stmt -> bindValue(':event_id', $id);
$stmt -> execute();
?>