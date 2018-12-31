<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$data['g1_p1'] = cleantext($_POST['g1_p1_1'])."-".cleantext($_POST['g1_p1_2'])."-".cleantext($_POST['g1_p1_3']);
if ($_POST['g1_p1_4'] != ""){$data['g1_p1'] = $data['g1_p1']."x".cleantext($_POST['g1_p1_4']);}

$data['g1_p2'] = cleantext($_POST['g1_p2_1'])."-".cleantext($_POST['g1_p2_2'])."-".cleantext($_POST['g1_p2_3']);
if ($_POST['g1_p2_4'] != ""){$data['g1_p2'] = $data['g1_p2']."x".cleantext($_POST['g1_p2_4']);}

$data['g2_p1'] = cleantext($_POST['g2_p1_1'])."-".cleantext($_POST['g2_p1_2'])."-".cleantext($_POST['g2_p1_3']);
if ($_POST['g1_p1_4'] != ""){$data['g2_p1'] = $data['g2_p1']."x".cleantext($_POST['g2_p1_4']);}

$data['g2_p2'] = cleantext($_POST['g2_p2_1'])."-".cleantext($_POST['g2_p2_2'])."-".cleantext($_POST['g2_p2_3']);
if ($_POST['g2_p2_4'] != ""){$data['g2_p2'] = $data['g2_p2']."x".cleantext($_POST['g2_p2_4']);}

$data['c_p1'] = cleantext($_POST['c_p1_1'])."-".cleantext($_POST['c_p1_2'])."-".cleantext($_POST['c_p1_3']);
if ($_POST['c_p1_4'] != ""){$data['c_p1'] = $data['c_p1']."x".cleantext($_POST['c_p1_4']);}

$data['c_p2'] = cleantext($_POST['c_p2_1'])."-".cleantext($_POST['c_p2_2'])."-".cleantext($_POST['c_p2_3']);
if ($_POST['c_p2_4'] != ""){$data['c_p2'] = $data['c_p2']."x".cleantext($_POST['c_p2_4']);}

$data['student_custody'] = cleantext($_POST['student_custody']);	
$data['student_custody_details'] = cleantext($_POST['student_custody_details']);	
$data['g1_name_last'] = cleantext($_POST['g1_name_last']);	
$data['g1_name_first'] = cleantext($_POST['g1_name_first']);	
$data['g1_relationship'] = cleantext($_POST['g1_relationship']);	
$data['g1_address'] = cleantext($_POST['g1_address']);	
$data['g1_city'] = cleantext($_POST['g1_city']);	
$data['g1_province'] = cleantext($_POST['g1_province']);	
$data['g1_postalcode'] = cleantext($_POST['g1_postalcode']);
$data['g1_postalcode'] = strtoupper($data['g1_postalcode']);	
$data['g1_p1_type'] = cleantext($_POST['g1_p1_type']);
$data['g1_p2_type'] = cleantext($_POST['g1_p2_type']);
$data['g1_email'] = cleantext($_POST['g1_email']);
$data['g1_notes'] = cleantext($_POST['g1_notes']);
$data['g2_name_last'] = cleantext($_POST['g2_name_last']);
$data['g2_name_first'] = cleantext($_POST['g2_name_first']);
$data['g2_relationship'] = cleantext($_POST['g2_relationship']);
$data['g2_address'] = cleantext($_POST['g2_address']);	
$data['g2_city'] = cleantext($_POST['g2_city']);	
$data['g2_province'] = cleantext($_POST['g2_province']);	
$data['g2_postalcode'] = cleantext($_POST['g2_postalcode']);
$data['g2_postalcode'] = strtoupper($data['g2_postalcode']);	
$data['g2_p1_type'] = cleantext($_POST['g2_p1_type']);
$data['g2_p2_type'] = cleantext($_POST['g2_p2_type']);
$data['g2_email'] = cleantext($_POST['g2_email']);
$data['g2_notes'] = cleantext($_POST['g2_notes']);
$data['c_name_last'] = cleantext($_POST['c_name_last']);
$data['c_name_first'] = cleantext($_POST['c_name_first']);
$data['c_relationship'] = cleantext($_POST['c_relationship']);
$data['c_p1_type'] = cleantext($_POST['c_p1_type']);
$data['c_p2_type'] = cleantext($_POST['c_p2_type']);

$stmt = $conn -> prepare("UPDATE ss_registrations SET
							student_custody = :student_custody,
							student_custody_details = :student_custody_details,
							g1_name_last = :g1_name_last,
							g1_name_first = :g1_name_first,
							g1_relationship = :g1_relationship,
							g1_address = :g1_address,
							g1_city = :g1_city,
							g1_province = :g1_province,
							g1_postalcode = :g1_postalcode,
							g1_p1_type = :g1_p1_type,
							g1_p1 = :g1_p1,
							g1_p2_type = :g1_p2_type,
							g1_p2 = :g1_p2,
							g1_email = :g1_email,
							g1_notes = :g1_notes,
							g2_name_last = :g2_name_last,
							g2_name_first = :g2_name_first,
							g2_relationship = :g2_relationship,		
							g2_address = :g2_address,
							g2_city = :g2_city,
							g2_province = :g2_province,
							g2_postalcode = :g2_postalcode,
							g2_p1_type = :g2_p1_type,
							g2_p1 = :g2_p1,
							g2_p2_type = :g2_p2_type,
							g2_p2 = :g2_p2,
							g2_email = :g2_email,
							g2_notes = :g2_notes,
							c_name_last = :c_name_last,
							c_name_first = :c_name_first,
							c_relationship = :c_relationship,
							c_p1_type = :c_p1_type,
							c_p1 = :c_p1,
							c_p2_type = :c_p2_type,
							c_p2 = :c_p2
							WHERE registration_id = :registration_id");

$stmt -> bindValue(':student_custody', $data['student_custody']);
$stmt -> bindValue(':student_custody_details', $data['student_custody_details']);
$stmt -> bindValue(':g1_name_last', $data['g1_name_last']);
$stmt -> bindValue(':g1_name_first', $data['g1_name_first']);
$stmt -> bindValue(':g1_relationship', $data['g1_relationship']);
$stmt -> bindValue(':g1_address', $data['g1_address']);
$stmt -> bindValue(':g1_city', $data['g1_city']);
$stmt -> bindValue(':g1_province', $data['g1_province']);
$stmt -> bindValue(':g1_postalcode', $data['g1_postalcode']);
$stmt -> bindValue(':g1_p1_type', $data['g1_p1_type']);
$stmt -> bindValue(':g1_p1', $data['g1_p1']);
$stmt -> bindValue(':g1_p2_type', $data['g1_p2_type']);			
$stmt -> bindValue(':g1_p2', $data['g1_p2']);
$stmt -> bindValue(':g1_email', $data['g1_email']);
$stmt -> bindValue(':g1_notes', $data['g1_notes']);
$stmt -> bindValue(':g2_name_last', $data['g2_name_last']);
$stmt -> bindValue(':g2_name_first', $data['g2_name_first']);
$stmt -> bindValue(':g2_relationship', $data['g2_relationship']);
$stmt -> bindValue(':g2_address', $data['g2_address']);
$stmt -> bindValue(':g2_city', $data['g2_city']);
$stmt -> bindValue(':g2_province', $data['g2_province']);
$stmt -> bindValue(':g2_postalcode', $data['g2_postalcode']);
$stmt -> bindValue(':g2_p1_type', $data['g2_p1_type']);
$stmt -> bindValue(':g2_p1', $data['g2_p1']);
$stmt -> bindValue(':g2_p2_type', $data['g2_p2_type']);			
$stmt -> bindValue(':g2_p2', $data['g2_p2']);
$stmt -> bindValue(':g2_email', $data['g2_email']);
$stmt -> bindValue(':g2_notes', $data['g2_notes']);
$stmt -> bindValue(':c_name_last', $data['c_name_last']);
$stmt -> bindValue(':c_name_first', $data['c_name_first']);
$stmt -> bindValue(':c_relationship', $data['c_relationship']);
$stmt -> bindValue(':c_p1_type', $data['c_p1_type']);
$stmt -> bindValue(':c_p1', $data['c_p1']);
$stmt -> bindValue(':c_p2_type', $data['c_p2_type']);			
$stmt -> bindValue(':c_p2', $data['c_p2']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page4'] = 1;

?>
