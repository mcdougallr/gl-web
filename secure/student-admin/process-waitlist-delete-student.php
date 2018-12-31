<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$id = cleantext($_POST['waitlist_id']);

$stmt = $conn -> prepare("DELETE FROM ss_waitlist WHERE waitlist_id = :waitlist_id");
$stmt -> bindValue(':waitlist_id', $id);
$stmt -> execute();
?>
