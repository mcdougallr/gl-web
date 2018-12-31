<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$data['student_name_last'] = cleantext($_POST['student_name_last']);
$data['student_name_first'] = cleantext($_POST['student_name_first']);
$data['student_name_common'] = cleantext($_POST['student_name_common']);
$data['student_dob'] = cleantext($_POST['student_dob']);
$data['student_sex'] = cleantext($_POST['student_sex']);
$data['student_grade'] = cleantext($_POST['student_grade']);
$data['student_email'] = cleantext($_POST['student_email']);

$stmt = $conn -> prepare("UPDATE ss_registrations SET
							student_name_last = :student_name_last, 
							student_name_first = :student_name_first,
							student_name_common = :student_name_common,
							student_dob = :student_dob,
							student_sex = :student_sex,
							student_email = :student_email,		
							student_grade = :student_grade		
							WHERE registration_id = :registration_id");

$stmt -> bindValue(':student_name_last',  $data['student_name_last']);
$stmt -> bindValue(':student_name_first', $data['student_name_first']);
$stmt -> bindValue(':student_name_common', $data['student_name_common']);	
$stmt -> bindValue(':student_dob', $data['student_dob']);
$stmt -> bindValue(':student_sex', $data['student_sex']);
$stmt -> bindValue(':student_email', $data['student_email']);
$stmt -> bindValue(':student_grade', $data['student_grade']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page3'] = 1;

?>
