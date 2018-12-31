<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$trip_id = cleantext($_POST['day_trip_id']);
$day_num = cleantext($_POST['day_num']);
$day_date = cleantext($_POST['day_date']); 
$day_loc = cleantext($_POST['day_loc']);

$stmt = $conn -> prepare("SELECT day_id FROM  fp_days
												WHERE day_trip_id = :day_trip_id AND day_num = :day_num OR day_trip_id = :day_trip_id  AND day_date = :day_date");
$stmt -> bindValue(':day_num', $day_num);
$stmt -> bindValue(':day_trip_id', $trip_id);
$stmt -> bindValue(':day_date', $day_date);
$stmt -> execute();
$duplicate = $stmt->fetchAll();

if ($duplicate == NULL)
{
	$stmt = $conn -> prepare("INSERT INTO fp_days
								(day_trip_id, day_num, day_date, day_loc) 
								VALUES
								(:day_trip_id, :day_num, :day_date, :day_loc)");

	$stmt -> bindValue(':day_trip_id', $trip_id);
	$stmt -> bindValue(':day_num', $day_num);
	$stmt -> bindValue(':day_date', $day_date);
	$stmt -> bindValue(':day_loc', $day_loc);
	$stmt -> execute();
}
?>
