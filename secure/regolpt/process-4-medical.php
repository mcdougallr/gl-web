<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$data['student_health_card'] = cleantext($_POST['student_health_card']);
$data['student_health_tetanus'] = cleantext($_POST['student_health_tetanus']);
$data['student_health_hospitalized'] = cleantext($_POST['student_health_hospitalized']);
$data['student_health_hospitalized_details'] = cleantext($_POST['student_health_hospitalized_details']);
$data['student_health_meds'] = cleantext($_POST['student_health_meds']);
$data['student_health_meds_details'] = cleantext($_POST['student_health_meds_details']);
$data['student_health_allergies'] = cleantext($_POST['student_health_allergies']);
$data['student_health_allergies_details'] = cleantext($_POST['student_health_allergies_details']);
$data['student_health_asthma'] = cleantext($_POST['student_health_asthma']);
$data['student_health_asthma_details'] = cleantext($_POST['student_health_asthma_details']);
$data['student_health_epipen'] = cleantext($_POST['student_health_epipen']);
$data['student_health_epipen_details'] = cleantext($_POST['student_health_epipen_details']);
$data['student_health_epilepsy'] = cleantext($_POST['student_health_epilepsy']);
$data['student_health_epilepsy_details'] = cleantext($_POST['student_health_epilepsy_details']);
$data['student_health_diabetes'] = cleantext($_POST['student_health_diabetes']);
$data['student_health_diabetes_details'] = cleantext($_POST['student_health_diabetes_details']);
$data['student_health_counselor'] = cleantext($_POST['student_health_counselor']);
$data['student_health_counselor_details'] = cleantext($_POST['student_health_counselor_details']);
$data['student_health_anxiety'] = cleantext($_POST['student_health_anxiety']);
$data['student_health_anxiety_details'] = cleantext($_POST['student_health_anxiety_details']);
$data['student_health_limitations'] = cleantext($_POST['student_health_limitations']);
$data['student_health_limitations_details'] = cleantext($_POST['student_health_limitations_details']);
$data['student_health_others'] = cleantext($_POST['student_health_others']);
$data['student_health_others_details'] = cleantext($_POST['student_health_others_details']);
$data['student_health_injuries'] = cleantext($_POST['student_health_injuries']);
$data['student_health_injuries_details'] = cleantext($_POST['student_health_injuries_details']);
$data['student_health_dietary_details'] = cleantext($_POST['student_health_dietary_details']);
$data['student_health_doctor'] = cleantext($_POST['student_health_doctor']);
$data['student_health_doctorphone'] = cleantext($_POST['student_health_doctorphone']);


$stmt = $conn -> prepare("UPDATE gl_registrations SET
													student_health_card = :student_health_card,
													student_health_tetanus = :student_health_tetanus,
													student_health_hospitalized = :student_health_hospitalized,
													student_health_hospitalized_details = :student_health_hospitalized_details,
													student_health_meds = :student_health_meds,
													student_health_meds_details = :student_health_meds_details,
													student_health_allergies = :student_health_allergies,
													student_health_allergies_details = :student_health_allergies_details,
													student_health_asthma = :student_health_asthma,
													student_health_asthma_details = :student_health_asthma_details,
													student_health_epipen = :student_health_epipen,
													student_health_epipen_details = :student_health_epipen_details,
													student_health_epilepsy = :student_health_epilepsy,
													student_health_epilepsy_details = :student_health_epilepsy_details,
													student_health_diabetes = :student_health_diabetes,
													student_health_diabetes_details = :student_health_diabetes_details,
													student_health_counselor = :student_health_counselor,
													student_health_counselor_details = :student_health_counselor_details,
													student_health_anxiety = :student_health_anxiety,
													student_health_anxiety_details = :student_health_anxiety_details,
													student_health_limitations = :student_health_limitations,
													student_health_limitations_details = :student_health_limitations_details,
													student_health_others = :student_health_others,
													student_health_others_details = :student_health_others_details,
													student_health_injuries = :student_health_injuries,
													student_health_injuries_details = :student_health_injuries_details,
													student_health_dietary_details = :student_health_dietary_details,
													student_health_doctor = :student_health_doctor,
													student_health_doctorphone = :student_health_doctorphone
													WHERE registration_id = :registration_id");

$stmt -> bindValue(':student_health_card', $data['student_health_card']);
$stmt -> bindValue(':student_health_tetanus', $data['student_health_tetanus']);
$stmt -> bindValue(':student_health_hospitalized', $data['student_health_hospitalized']);
$stmt -> bindValue(':student_health_hospitalized_details', $data['student_health_hospitalized_details']);
$stmt -> bindValue(':student_health_meds', $data['student_health_meds']);
$stmt -> bindValue(':student_health_meds_details', $data['student_health_meds_details']);
$stmt -> bindValue(':student_health_allergies', $data['student_health_allergies']);
$stmt -> bindValue(':student_health_allergies_details', $data['student_health_allergies_details']);
$stmt -> bindValue(':student_health_asthma', $data['student_health_asthma']);
$stmt -> bindValue(':student_health_asthma_details', $data['student_health_asthma_details']);
$stmt -> bindValue(':student_health_epipen', $data['student_health_epipen']);
$stmt -> bindValue(':student_health_epipen_details', $data['student_health_epipen_details']);
$stmt -> bindValue(':student_health_epilepsy', $data['student_health_epilepsy']);
$stmt -> bindValue(':student_health_epilepsy_details', $data['student_health_epilepsy_details']);
$stmt -> bindValue(':student_health_diabetes', $data['student_health_diabetes']);
$stmt -> bindValue(':student_health_diabetes_details', $data['student_health_diabetes_details']);
$stmt -> bindValue(':student_health_counselor', $data['student_health_counselor']);
$stmt -> bindValue(':student_health_counselor_details', $data['student_health_counselor_details']);
$stmt -> bindValue(':student_health_anxiety', $data['student_health_anxiety']);
$stmt -> bindValue(':student_health_anxiety_details', $data['student_health_anxiety_details']);
$stmt -> bindValue(':student_health_limitations', $data['student_health_limitations']);
$stmt -> bindValue(':student_health_limitations_details', $data['student_health_limitations_details']);
$stmt -> bindValue(':student_health_others', $data['student_health_others']);
$stmt -> bindValue(':student_health_others_details', $data['student_health_others_details']);
$stmt -> bindValue(':student_health_injuries', $data['student_health_injuries']);
$stmt -> bindValue(':student_health_injuries_details', $data['student_health_injuries_details']);
$stmt -> bindValue(':student_health_dietary_details', $data['student_health_dietary_details']);
$stmt -> bindValue(':student_health_doctor', $data['student_health_doctor']);
$stmt -> bindValue(':student_health_doctorphone', $data['student_health_doctorphone']);
$stmt -> bindValue(':registration_id', $_SESSION['registration_id']);

$stmt -> execute();

$_SESSION['page5'] = 1;

?>
