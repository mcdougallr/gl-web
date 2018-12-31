<?php
session_start();

include ('../shared/dbconnect.php');

$stmt = $conn -> prepare("DELETE FROM ss_registrations WHERE registration_id = :registration_id");
$stmt -> bindValue(':registration_id', $_SESSION['student_id']);
$stmt -> execute();

?>







