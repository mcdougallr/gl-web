<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$field_name = cleantext($_POST['field_name']);
$field_val = cleantext($_POST['field_val']);
$trip_id = cleantext($_POST['trip_id']);

$stmt = $conn -> prepare("UPDATE fp_trips SET ".$field_name." = :".$field_name." WHERE trip_id = :trip_id");
$stmt -> bindValue(":".$field_name, $field_val);
$stmt -> bindValue(":trip_id", $trip_id);

$stmt -> execute();