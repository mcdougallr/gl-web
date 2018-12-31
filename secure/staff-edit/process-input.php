<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$name = cleantext($_POST['name']);
$val = cleantext($_POST['val']);
$staff = cleantext($_POST['staff']);

$stmt = $conn -> prepare("UPDATE staff SET ".$name." = :".$name." WHERE staff_id = :staff_id");
$stmt -> bindValue(":".$name, $val);
$stmt -> bindValue(':staff_id', $staff);
$stmt -> execute();

if ($name == "admin_archive" AND $val == "Yes")
{
	$stmt = $conn -> prepare("UPDATE staff SET staff_access = 0 WHERE staff_id = :staff_id");
	$stmt -> bindValue(':staff_id', $staff);
	$stmt -> execute();
}

if ($name == "admin_archive" AND $val == "No")
{
	$stmt = $conn -> prepare("UPDATE staff SET staff_access = 1 WHERE staff_id = :staff_id");
	$stmt -> bindValue(':staff_id', $staff);
	$stmt -> execute();
}