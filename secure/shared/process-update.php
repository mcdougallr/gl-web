<?php
session_start();
include ('dbconnect.php');
include ('clean.php');

$staff_id = cleantext($_POST['staff_id']);
$date = date('Y-m-d');

$stmt = $conn -> prepare("UPDATE staff SET 
													staff_update_date = :staff_update_date								
													WHERE staff_id = :staff_id");

$stmt -> bindValue(':staff_update_date', $date);
$stmt -> bindValue(':staff_id', $staff_id);

$stmt -> execute();