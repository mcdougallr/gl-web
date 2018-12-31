<?php
session_start();

include ('../shared/dbconnect.php');

$stmt = $conn -> prepare("CREATE TEMPORARY TABLE temp_table 
							SELECT * FROM ss_registrations WHERE registration_id = :registration_id");
$stmt -> bindValue(':registration_id', $_SESSION['student_id']);
$stmt -> execute();

$stmt = $conn -> prepare("ALTER TABLE temp_table MODIFY registration_id INT(11)");
$stmt -> execute();

$stmt = $conn -> prepare("UPDATE temp_table SET registration_id = NULL");
$stmt -> execute();

$stmt = $conn -> prepare("INSERT INTO ss_registrations SELECT * from temp_table");
$stmt -> execute();

$stmt = $conn -> prepare("DROP TEMPORARY TABLE IF EXISTS temp_table");
$stmt -> execute();

header("Location: ../student-admin/index.php");

?>
