<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$rid = cleantext($_POST['registration_id']);
$session = cleantext($_POST['accepted_session']);

$stmt = $conn -> prepare("UPDATE ss_registrations SET 
											accepted_session = :accepted_session
											WHERE registration_id = :registration_id");

$stmt -> bindValue(':accepted_session', $session);
$stmt -> bindValue(':registration_id', $rid);

$stmt -> execute();

?>
