<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$deduction_id = cleantext($_POST['val']);
$stmt = $conn -> prepare("DELETE FROM staff_paydeductions WHERE deduction_id = :deduction_id");
$stmt -> bindValue(':deduction_id', $deduction_id);
$stmt -> execute();