<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$tripstudent_id = cleantext($_POST['tripstudent_id']);

$stmt = $conn -> prepare("DELETE FROM fp_tripstudents WHERE tripstudent_id = :tripstudent_id");
$stmt -> bindValue(':tripstudent_id', $tripstudent_id);
$stmt -> execute();

?>
