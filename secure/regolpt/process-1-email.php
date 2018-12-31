<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$data['student_email'] = cleantext($_POST['student_email']);

if ($_SESSION['registration_id'] != 0)
{
	$stmt = $conn -> prepare("UPDATE gl_registrations SET
														student_email = :student_email																
														WHERE registration_id = :registration_id");

	$stmt -> bindValue(':student_email', $data['student_email']);
	$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

	$stmt -> execute();
}
else
{
	$data['registration_date'] = date("Y-m-d");
	$data['registration_time'] = date("H:i:s");
	
	$stmt = $conn -> prepare("INSERT INTO gl_registrations 
														(registration_code,registration_date,registration_time,student_email)
														VALUES 
														(:registration_code,:registration_date,:registration_time,:student_email)");
														
	$stmt -> bindValue(':registration_code', $_SESSION['registration_code']);
	$stmt -> bindValue(':registration_date',  $data['registration_date']);
	$stmt -> bindValue(':registration_time', $data['registration_time']);
	$stmt -> bindValue(':student_email', $data['student_email']);
	
	$stmt -> execute();
	
	$_SESSION['registration_id'] = $conn -> lastInsertId();
	$_SESSION['page2'] = 1;
}

?>
