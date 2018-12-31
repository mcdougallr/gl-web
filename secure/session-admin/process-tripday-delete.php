<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$day_id = cleantext($_POST['day_id']);

$stmt = $conn -> prepare("DELETE FROM fp_days WHERE day_id = :day_id");
$stmt -> bindValue(':day_id', $day_id);
$stmt -> execute();

?>
