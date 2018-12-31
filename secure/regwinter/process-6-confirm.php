<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$data['confirm_social_media'] = cleantext($_POST['confirm_social_media']);
$data['confirm_photo'] = cleantext($_POST['confirm_photo']);
	
$stmt = $conn -> prepare("UPDATE ss_registrations SET 
							confirm_social_media = :confirm_social_media,
							confirm_photo = :confirm_photo
							WHERE registration_id = :registration_id");
													
$stmt -> bindValue(':confirm_social_media', $data['confirm_social_media']);
$stmt -> bindValue(':confirm_photo', $data['confirm_photo']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);
$stmt -> execute();

$_SESSION['page7'] = 1;

?>
