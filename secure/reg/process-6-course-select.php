<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$data['selected_program'] = cleantext($_POST['selected_program']);

$stmt = $conn->prepare("select selected_program from ss_registrations where registration_id = :reg_id");
$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
$stmt-> execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if ($student['selected_program'] != $data['selected_program'])
{
	$selected_session1 = "";
	$selected_session2 = "";
	$selected_session3 = "";
	$selected_alternate	= "";
		
	$stmt = $conn -> prepare("UPDATE ss_registrations SET 
								selected_session1 = :selected_session1,
								selected_session2 = :selected_session2,
								selected_session3 = :selected_session3,
								selected_alternate = :selected_alternate
								WHERE registration_id = :registration_id");

	$stmt -> bindValue(':selected_session1', $selected_session1);
	$stmt -> bindValue(':selected_session2', $selected_session2);
	$stmt -> bindValue(':selected_session3', $selected_session3);
	$stmt -> bindValue(':selected_alternate', $selected_alternate);
	$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);
	
	$stmt -> execute();
}

$stmt = $conn -> prepare("UPDATE ss_registrations 
							SET selected_program = :selected_program
							WHERE registration_id = :registration_id");

$stmt -> bindValue(':selected_program', $data['selected_program']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page7'] = 1;

?>
