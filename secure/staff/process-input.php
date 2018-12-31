<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$name = cleantext($_POST['name']);
$val = cleantext($_POST['val']);

$stmt = $conn -> prepare("UPDATE staff SET ".$name." = :".$name." WHERE staff_id = :staff_id");

$stmt -> bindValue(":".$name, $val);
$stmt -> bindValue(':staff_id', $_SESSION['gl_staff_id']);

$stmt -> execute();