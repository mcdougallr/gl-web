<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$trip_id = cleantext($_POST['tripstaff_trip_id']);
$staff_id = cleantext($_POST['tripstaff_staff_id']); 
$tt = cleantext($_POST['tripstaff_tt']);

$stmt = $conn -> prepare("SELECT tripstaff_id FROM  fp_tripstaff
												WHERE tripstaff_staff_id = :tripstaff_staff_id AND tripstaff_trip_id = :tripstaff_trip_id AND tripstaff_tt = :tripstaff_tt");
$stmt -> bindValue(':tripstaff_staff_id', $staff_id);
$stmt -> bindValue(':tripstaff_trip_id', $trip_id);
$stmt -> bindValue(':tripstaff_tt', $tt);
$stmt -> execute();
$duplicate = $stmt->fetchAll();

if ($duplicate == NULL)
{
	$stmt = $conn -> prepare("INSERT INTO fp_tripstaff
								(tripstaff_staff_id, tripstaff_trip_id, tripstaff_tt) 
								VALUES
								(:tripstaff_staff_id, :tripstaff_trip_id, :tripstaff_tt)");

	$stmt -> bindValue(':tripstaff_staff_id', $staff_id);
	$stmt -> bindValue(':tripstaff_trip_id', $trip_id);
	$stmt -> bindValue(':tripstaff_tt', $tt);
	$stmt -> execute();
}
?>
