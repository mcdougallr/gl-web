<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$student_id = cleantext($_POST['student_id']);	
$count = count($_POST['data_id']);

for ($i = 0; $i < $count; $i++) 
{
	$data_id = cleantext($_POST['data_id'][$i]);
	$data_assess_id = cleantext($_POST['data_assess_id'][$i]);
	$data_value = cleantext($_POST['data_value'][$i]);
	
	if ($data_id != "") 
	{
		$stmt = $conn -> prepare("UPDATE ss_assess_data SET 
									data_student_id = :data_student_id,
									data_assess_id = :data_assess_id,
									data_value = :data_value
									WHERE data_id = :data_id");
	
		$stmt -> bindValue(':data_student_id', $student_id);
		$stmt -> bindValue(':data_assess_id', $data_assess_id);
		$stmt -> bindValue(':data_value', $data_value);
		$stmt -> bindValue(':data_id', $data_id);
		
		$stmt -> execute();
	}
	else 
	{
		$stmt = $conn -> prepare("INSERT INTO ss_assess_data
									(data_student_id,data_assess_id,data_value) 
									VALUES
									(:data_student_id,:data_assess_id,:data_value)");
	
		$stmt -> bindValue(':data_student_id', $student_id);
		$stmt -> bindValue(':data_assess_id', $data_assess_id);
		$stmt -> bindValue(':data_value', $data_value);
		
		$stmt -> execute();
	}
}
?>