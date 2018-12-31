<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$deduction_date = cleantext($_POST['deduction_date']);
$deduction_amount = cleantext($_POST['deduction_amount']);
$staff = cleantext($_POST['staff']);

$stmt = $conn -> prepare("INSERT INTO staff_paydeductions 
															(deduction_date,deduction_staff_id,deduction_amount) 
															VALUES
															(:deduction_date,:deduction_staff_id,:deduction_amount)");
$stmt -> bindValue(':deduction_date', $deduction_date);
$stmt -> bindValue(':deduction_staff_id', $staff);
$stmt -> bindValue(':deduction_amount', $deduction_amount);

$stmt -> execute();

?>