<?php
session_start();
include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$visit_id = cleantext($_POST['id']);

$stmt = $conn -> prepare("UPDATE schedule_visit SET visit_confirm = 1 WHERE visit_id = :visit_id");
$stmt -> bindValue(':visit_id', $visit_id);
$stmt -> execute();

?>