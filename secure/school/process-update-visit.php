<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

	$submit_date = date('Y-m-d');
	$visit_id = cleantext($_POST['visit_id']);	
	$teacher_name = cleantext($_POST['teacher_name']);
	$grade = cleantext($_POST['grade']);
	$student_num = cleantext($_POST['student_num']);
	$program_id = cleantext($_POST['program_id']);
	$visit_notes = cleantext($_POST['visit_notes']);
	
	$stmt = $conn -> prepare("UPDATE schedule_visit SET 
							submit_date = :submit_date,
							teacher_name = :teacher_name,
							grade = :grade,
							student_num = :student_num,
							program_id = :program_id,
							visit_notes = :visit_notes
							WHERE visit_id = :visit_id");
	
	$stmt -> bindValue(':submit_date', $submit_date);
	$stmt -> bindValue(':teacher_name', $teacher_name);
	$stmt -> bindValue(':grade', $grade);
	$stmt -> bindValue(':student_num', $student_num);
	$stmt -> bindValue(':program_id', $program_id);
	$stmt -> bindValue(':visit_notes', $visit_notes);
	$stmt -> bindValue(':visit_id', $visit_id);
	$stmt -> execute();
?>