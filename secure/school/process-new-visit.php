<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$submit_date = date('Y-m-d');
$school_id = cleantext($_POST['school_id']);
$teacher_name = cleantext($_POST['teacher_name']);
$grade = cleantext($_POST['grade']);
$student_num = cleantext($_POST['student_num']);
$program_id = cleantext($_POST['program_id']);
$visit_notes = cleantext($_POST['visit_notes']);

$stmt = $conn -> prepare("INSERT INTO schedule_visit
							(school_id,submit_date,teacher_name,grade,student_num,program_id,visit_notes)
							VALUES 
							(:school_id,:submit_date,:teacher_name,:grade,:student_num,:program_id,:visit_notes)");

$stmt -> bindValue(':school_id', $school_id);
$stmt -> bindValue(':submit_date', $submit_date);
$stmt -> bindValue(':teacher_name', $teacher_name);
$stmt -> bindValue(':grade', $grade);
$stmt -> bindValue(':student_num', $student_num);
$stmt -> bindValue(':program_id', $program_id);
$stmt -> bindValue(':visit_notes', $visit_notes);
$stmt -> execute();

$type_id = $conn -> lastInsertId();

$stmt = $conn->prepare("INSERT INTO schedule_events (event_date, event_type, event_type_id) VALUES (:event_date, :event_type, :event_type_id);");
$stmt->bindValue(':event_date', "0000-00-00");
$stmt->bindValue(':event_type', "V");
$stmt->bindValue(':event_type_id', $type_id);
$stmt->execute();
?>