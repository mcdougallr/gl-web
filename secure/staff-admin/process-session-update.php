<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$sd_id = cleantext($_POST['sd_id']);

if (isset($_POST['sd_start']))
{
	$data = cleantext($_POST['sd_start']);
	$stmt = $conn -> prepare("UPDATE gl_staff_session_days SET sd_start = :sd_start WHERE sd_id = :sd_id");
	$stmt -> bindValue(':sd_start', $data);
}
else if (isset($_POST['sd_end']))
{
	$data = cleantext($_POST['sd_end']);
	$stmt = $conn -> prepare("UPDATE gl_staff_session_days SET sd_end = :sd_end WHERE sd_id = :sd_id");
	$stmt -> bindValue(':sd_end', $data);
}
else if (isset($_POST['sd_description']))
{
	$data = cleantext($_POST['sd_description']);
	$stmt = $conn -> prepare("UPDATE gl_staff_session_days SET sd_description = :sd_description WHERE sd_id = :sd_id");
	$stmt -> bindValue(':sd_description', $data);
}
else if (isset($_POST['sd_percentage']))
{
	$data = cleantext($_POST['sd_percentage']);
	$stmt = $conn -> prepare("UPDATE gl_staff_session_days SET sd_percentage = :sd_percentage WHERE sd_id = :sd_id");
	$stmt -> bindValue(':sd_percentage', $data);
}
		
$stmt -> bindValue(':sd_id', $sd_id);
$stmt -> execute();