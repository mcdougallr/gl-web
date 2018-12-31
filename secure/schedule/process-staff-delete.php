<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$workday_id = cleantext($_POST['workday_id']);

$stmt = $conn -> prepare("DELETE FROM staff_workdays WHERE workday_id = :workday_id");
$stmt -> bindValue(':workday_id', $workday_id);
$stmt -> execute();

?>
