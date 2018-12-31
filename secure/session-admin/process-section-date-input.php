<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$field_name = cleantext($_POST['field_name']);
$field_val = cleantext($_POST['field_val']);
$summer_id = cleantext($_POST['summer_id']);

$stmt = $conn -> prepare("UPDATE schedule_summer SET ".$field_name." = :".$field_name." WHERE summer_id = :summer_id");
$stmt -> bindValue(":".$field_name, $field_val);
$stmt -> bindValue(":summer_id", $summer_id);

$stmt -> execute();