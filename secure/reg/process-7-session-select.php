<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (!isset($_POST['selected_session1'])) {$data['selected_session1'] = 0;}
else {$data['selected_session1'] = cleantext($_POST['selected_session1']);}

if (!isset($_POST['selected_session2'])) {$data['selected_session2'] = 0;}
else {$data['selected_session2'] = cleantext($_POST['selected_session2']);}

if (!isset($_POST['selected_alternate'])) {$data['selected_alternate'] = "No";}
else {$data['selected_alternate'] = cleantext($_POST['selected_alternate']);}

$data['selected_placement'] = cleantext($_POST['selected_placement']);
	
$stmt = $conn -> prepare("UPDATE ss_registrations SET
							selected_session1 = :selected_session1,
							selected_session2 = :selected_session2,
							selected_alternate = :selected_alternate,
							selected_placement = :selected_placement
							WHERE registration_id = :registration_id");
													
$stmt -> bindValue(':selected_session1', $data['selected_session1']);
$stmt -> bindValue(':selected_session2', $data['selected_session2']);
$stmt -> bindValue(':selected_alternate', $data['selected_alternate']);
$stmt -> bindValue(':selected_placement', $data['selected_placement']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page8'] = 1;	

?>