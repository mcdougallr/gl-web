<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$data['staff_password'] = cleantext($_POST['password']);

$stmt = $conn -> prepare("UPDATE staff SET 
													staff_password = :staff_password
													WHERE staff_id = :staff_id");

$stmt -> bindValue(':staff_password', $data['staff_password']);
$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);

$stmt -> execute();
