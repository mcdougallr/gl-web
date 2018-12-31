<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$field_name = cleantext($_POST['field_name']);
$field_val = cleantext($_POST['field_val']);
$session_id = cleantext($_POST['session_id']);

$stmt = $conn -> prepare("UPDATE ss_sessions SET ".$field_name." = :".$field_name." WHERE session_id = :session_id");
$stmt -> bindValue(":".$field_name, $field_val);
$stmt -> bindValue(":session_id", $session_id);

$stmt -> execute();