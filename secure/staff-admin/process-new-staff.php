<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$date = date("Y-m-d");
$stmt = $conn -> prepare("INSERT INTO staff
													(staff_update_date) 
													VALUES
													(:staff_update_date) ");
$stmt -> bindValue(':staff_update_date', $date);
$stmt -> execute();

$sid = $conn -> lastInsertId();

header("Location: ../staff-edit/index.php?sid=".$sid);

?>
