<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$event_id = cleantext($_POST['id']);

$stmt = $conn -> prepare("UPDATE schedule_events SET event_date = :event_date WHERE event_id = :event_id");
$stmt -> bindValue(':event_date', $_SESSION['date']);
$stmt -> bindValue(':event_id', $event_id);
$stmt -> execute();

?>