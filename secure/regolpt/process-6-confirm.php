<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$data['confirm_social_media'] = cleantext($_POST['confirm_social_media']);
$data['confirm_photo'] = cleantext($_POST['confirm_photo']);
$data['selected_program'] = 11;
$data['selected_session1'] = 40;
	
$stmt = $conn -> prepare("UPDATE gl_registrations SET 
													confirm_social_media = :confirm_social_media,
													confirm_photo = :confirm_photo,
													selected_program = :selected_program,
													selected_session1 = :selected_session1
													WHERE registration_id = :registration_id");
													
$stmt -> bindValue(':confirm_social_media', $data['confirm_social_media']);
$stmt -> bindValue(':confirm_photo', $data['confirm_photo']);
$stmt -> bindValue(':selected_program', $data['selected_program']);
$stmt -> bindValue(':selected_session1', $data['selected_session1']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);
$stmt -> execute();

$_SESSION['page7'] = 1;

?>
