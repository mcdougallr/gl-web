<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$name = cleantext($_POST['name']);
$val = cleantext($_POST['val']);

$stmt = $conn -> prepare("UPDATE ss_registrations SET ".$name." = :".$name." WHERE registration_id = :registration_id");
$stmt -> bindValue(":".$name, $val);
$stmt -> bindValue(':registration_id', $_SESSION['student_id']);
$stmt -> execute();

if ($name == "selected_session1")
{
	$stmt = $conn -> prepare("SELECT program_id FROM ss_sessions
								LEFT JOIN ss_programs ON ss_programs.program_code = ss_sessions.session_program_code
								WHERE session_id = :session_id");
	$stmt -> bindValue(':session_id', $val);
	$stmt -> execute();
	$program = $stmt -> fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn -> prepare("UPDATE ss_registrations SET selected_program = :selected_program WHERE registration_id = :registration_id");
	$stmt -> bindValue(":selected_program", $program['program_id']);
	$stmt -> bindValue(':registration_id', $_SESSION['student_id']);
	$stmt -> execute();
}
