<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$student = cleantext($_POST['waitlist_student_id']);
$session = cleantext($_POST['waitlist_session_id']);

$stmt = $conn -> prepare("INSERT INTO ss_waitlist
							(waitlist_student_id, waitlist_session_id) 
							VALUES
							(:waitlist_student_id, :waitlist_session_id) ");
$stmt -> bindValue(':waitlist_student_id', $student);
$stmt -> bindValue(':waitlist_session_id', $session);
$stmt -> execute();
?>
