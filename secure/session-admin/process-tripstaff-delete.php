<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$tripstaff_id = cleantext($_POST['tripstaff_id']);

$stmt = $conn -> prepare("DELETE FROM fp_tripstaff WHERE tripstaff_id = :tripstaff_id");
$stmt -> bindValue(':tripstaff_id', $tripstaff_id);
$stmt -> execute();

?>
