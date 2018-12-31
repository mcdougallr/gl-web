<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$data['completed_oe_year'] = cleantext($_POST['completed_oe_year']);
$data['completed_gap_year'] = cleantext($_POST['completed_gap_year']);
$data['completed_q_year'] = cleantext($_POST['completed_q_year']);
$data['completed_olp_year'] = cleantext($_POST['completed_olp_year']);
$data['completed_outreach_year'] = cleantext($_POST['completed_outreach_year']);
$data['completed_op_year'] = cleantext($_POST['completed_op_year']);
$data['completed_os_year'] = cleantext($_POST['completed_os_year']);
$data['completed_solo_year'] = cleantext($_POST['completed_solo_year']);
$data['completed_wic_year'] = cleantext($_POST['completed_wic_year']);
$data['completed_lt_year'] = cleantext($_POST['completed_lt_year']);
$data['completed_kic_year'] = cleantext($_POST['completed_kic_year']);
$data['student_experience'] = cleantext($_POST['student_experience']);

if ($data['completed_oe_year'] != "") {$data['completed_oe'] = "Yes";}
if ($data['completed_gap_year'] != "") {$data['completed_gap']="Yes";}
if ($data['completed_q_year'] != "") {$data['completed_q']="Yes";}
if ($data['completed_olp_year'] != "") {$data['completed_olp']="Yes";}
if ($data['completed_outreach_year'] != "") {$data['completed_outreach']="Yes";}
if ($data['completed_op_year'] != "") {$data['completed_op']="Yes";}
if ($data['completed_os_year'] != "") {$data['completed_os']="Yes";}
if ($data['completed_solo_year'] != "") {$data['completed_solo']="Yes";}
if ($data['completed_wic_year'] != "") {$data['completed_wic']="Yes";}
if ($data['completed_lt_year'] != "") {$data['completed_lt']="Yes";}
if ($data['completed_kic_year'] != "") {$data['completed_kic']="Yes";}
	
if (!isset($data['completed_oe'])) {$data['completed_oe'] = "No";}
if (!isset($data['completed_gap'])) {$data['completed_gap'] = "No";}
if (!isset($data['completed_q'])) {$data['completed_q'] = "No";}	
if (!isset($data['completed_olp'])) {$data['completed_olp']="No";}	
if (!isset($data['completed_outreach'])) {$data['completed_outreach']="No";}	
if (!isset($data['completed_op'])) {$data['completed_op']="No";}	
if (!isset($data['completed_os'])) {$data['completed_os']="No";}	
if (!isset($data['completed_solo'])) {$data['completed_solo']="No";}	
if (!isset($data['completed_wic'])) {$data['completed_wic']="No";}	
if (!isset($data['completed_lt'])) {$data['completed_lt']="No";}	
if (!isset($data['completed_kic'])) {$data['completed_kic']="No";}	
	
$stmt = $conn -> prepare("UPDATE ss_registrations SET
							completed_oe = :completed_oe,
							completed_oe_year = :completed_oe_year,
							completed_gap = :completed_gap,
							completed_gap_year = :completed_gap_year,
							completed_q = :completed_q,
							completed_q_year = :completed_q_year,
							completed_olp = :completed_olp,
							completed_olp_year = :completed_olp_year,
							completed_outreach = :completed_outreach,
							completed_outreach_year = :completed_outreach_year,
							completed_op = :completed_op,
							completed_op_year = :completed_op_year,
							completed_os = :completed_os,
	 						completed_os_year = :completed_os_year,
	 						completed_solo = :completed_solo,
	 						completed_solo_year = :completed_solo_year,
							completed_wic = :completed_wic,
							completed_wic_year = :completed_wic_year,
							completed_lt = :completed_lt,
							completed_lt_year = :completed_lt_year,
							completed_kic = :completed_kic,
							completed_kic_year = :completed_kic_year,
							student_experience = :student_experience
							WHERE registration_id = :registration_id");
	
$stmt -> bindValue(':completed_oe', $data['completed_oe']);
$stmt -> bindValue(':completed_oe_year', $data['completed_oe_year']);
$stmt -> bindValue(':completed_gap', $data['completed_gap']);
$stmt -> bindValue(':completed_gap_year', $data['completed_gap_year']);
$stmt -> bindValue(':completed_q', $data['completed_q']);
$stmt -> bindValue(':completed_q_year', $data['completed_q_year']);
$stmt -> bindValue(':completed_olp', $data['completed_olp']);
$stmt -> bindValue(':completed_olp_year', $data['completed_olp_year']);
$stmt -> bindValue(':completed_outreach', $data['completed_outreach']);
$stmt -> bindValue(':completed_outreach_year', $data['completed_outreach_year']);
$stmt -> bindValue(':completed_op', $data['completed_op']);
$stmt -> bindValue(':completed_op_year', $data['completed_op_year']);
$stmt -> bindValue(':completed_os', $data['completed_os']);
$stmt -> bindValue(':completed_os_year', $data['completed_os_year']);
$stmt -> bindValue(':completed_solo', $data['completed_solo']);
$stmt -> bindValue(':completed_solo_year', $data['completed_solo_year']);
$stmt -> bindValue(':completed_wic', $data['completed_wic']);
$stmt -> bindValue(':completed_lt_year', $data['completed_lt_year']);
$stmt -> bindValue(':completed_lt', $data['completed_lt']);
$stmt -> bindValue(':completed_wic_year', $data['completed_wic_year']);
$stmt -> bindValue(':completed_kic', $data['completed_kic']);
$stmt -> bindValue(':completed_kic_year', $data['completed_kic_year']);
$stmt -> bindValue(':student_experience', $data['student_experience']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page6'] = 1;
	
?>
