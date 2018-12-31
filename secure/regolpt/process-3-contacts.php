<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/functions.php');

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
$data['g1_hp'] = cleantext($_POST['g1_hp']);
$data['g1_wp'] = cleantext($_POST['g1_wp']);
$data['g1_cp'] = cleantext($_POST['g1_cp']);
$data['g1_sp'] = cleantext($_POST['g1_sp']);
$data['g1_email'] = cleantext($_POST['g1_email']);
$data['g1_notes'] = cleantext($_POST['g1_notes']);
$data['g2_name_last'] = cleantext($_POST['g2_name_last']);
$data['g2_name_first'] = cleantext($_POST['g2_name_first']);
$data['g2_relationship'] = cleantext($_POST['g2_relationship']);
$data['g2_hp'] = cleantext($_POST['g2_hp']);
$data['g2_wp'] = cleantext($_POST['g2_wp']);
$data['g2_cp'] = cleantext($_POST['g2_cp']);
$data['g2_sp'] = cleantext($_POST['g2_sp']);
$data['g2_email'] = cleantext($_POST['g2_email']);
$data['g2_notes'] = cleantext($_POST['g2_notes']);
$data['c_name_last'] = cleantext($_POST['c_name_last']);
$data['c_name_first'] = cleantext($_POST['c_name_first']);
$data['c_relationship'] = cleantext($_POST['c_relationship']);
$data['c_hp'] = cleantext($_POST['c_hp']);
$data['c_cp'] = cleantext($_POST['c_cp']);

$stmt = $conn -> prepare("UPDATE gl_registrations SET
													student_custody = :student_custody,
													student_custody_details = :student_custody_details,
													g1_name_last = :g1_name_last,
													g1_name_first = :g1_name_first,
													g1_relationship = :g1_relationship,
													g1_address = :g1_address,
													g1_city = :g1_city,
													g1_province = :g1_province,
													g1_postalcode = :g1_postalcode,
													g1_hp = :g1_hp,
													g1_wp = :g1_wp,
													g1_cp = :g1_cp,
													g1_sp = :g1_sp,
													g1_email = :g1_email,
													g1_notes = :g1_notes,
													g2_name_last = :g2_name_last,
													g2_name_first = :g2_name_first,
													g2_relationship = :g2_relationship,																
													g2_hp = :g2_hp,
													g2_wp = :g2_wp,
													g2_cp = :g2_cp,
													g2_sp = :g2_sp,
													g2_email = :g2_email,
													g2_notes = :g2_notes,
													c_name_last = :c_name_last,
													c_name_first = :c_name_first,
													c_relationship = :c_relationship,
													c_hp = :c_hp,
													c_cp = :c_cp
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
$stmt -> bindValue(':g1_hp', $data['g1_hp']);
$stmt -> bindValue(':g1_wp', $data['g1_wp']);
$stmt -> bindValue(':g1_cp', $data['g1_cp']);			
$stmt -> bindValue(':g1_sp', $data['g1_sp']);
$stmt -> bindValue(':g1_email', $data['g1_email']);
$stmt -> bindValue(':g1_notes', $data['g1_notes']);
$stmt -> bindValue(':g2_name_last', $data['g2_name_last']);
$stmt -> bindValue(':g2_name_first', $data['g2_name_first']);
$stmt -> bindValue(':g2_relationship', $data['g2_relationship']);
$stmt -> bindValue(':g2_hp', $data['g2_hp']);
$stmt -> bindValue(':g2_wp', $data['g2_wp']);
$stmt -> bindValue(':g2_cp', $data['g2_cp']);			
$stmt -> bindValue(':g2_sp', $data['g2_sp']);
$stmt -> bindValue(':g2_email', $data['g2_email']);
$stmt -> bindValue(':g2_notes', $data['g2_notes']);
$stmt -> bindValue(':c_name_last', $data['c_name_last']);
$stmt -> bindValue(':c_name_first', $data['c_name_first']);
$stmt -> bindValue(':c_relationship', $data['c_relationship']);
$stmt -> bindValue(':c_hp', $data['c_hp']);
$stmt -> bindValue(':c_cp', $data['c_cp']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page4'] = 1;

?>
