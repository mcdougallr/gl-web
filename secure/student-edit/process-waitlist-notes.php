<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$id = cleantext($_POST['id']);
$val = cleantext($_POST['val']);

$stmt = $conn -> prepare("UPDATE ss_waitlist SET waitlist_notes = :waitlist_notes WHERE waitlist_id = :waitlist_id");
$stmt -> bindValue(':waitlist_notes', $val);
$stmt -> bindValue(':waitlist_id', $id);
$stmt -> execute();
?>