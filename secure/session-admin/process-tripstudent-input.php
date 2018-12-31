<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$trip_id = cleantext($_POST['tripstudent_trip_id']);
$student_id = cleantext($_POST['tripstudent_student_id']); 
$se = cleantext($_POST['tripstudent_extra']);

$stmt = $conn -> prepare("SELECT tripstudent_id FROM  fp_tripstudents
												WHERE tripstudent_student_id = :tripstudent_student_id AND tripstudent_trip_id = :tripstudent_trip_id AND tripstudent_extra = :tripstudent_extra");
$stmt -> bindValue(':tripstudent_student_id', $student_id);
$stmt -> bindValue(':tripstudent_trip_id', $trip_id);
$stmt -> bindValue(':tripstudent_extra', $se);
$stmt -> execute();
$duplicate = $stmt->fetchAll();

if ($duplicate == NULL)
{
	$stmt = $conn -> prepare("INSERT INTO fp_tripstudents
								(tripstudent_student_id, tripstudent_trip_id, tripstudent_extra) 
								VALUES
								(:tripstudent_student_id, :tripstudent_trip_id, :tripstudent_extra)");

	$stmt -> bindValue(':tripstudent_student_id', $student_id);
	$stmt -> bindValue(':tripstudent_trip_id', $trip_id);
	$stmt -> bindValue(':tripstudent_extra', $se);
	$stmt -> execute();
}
?>
