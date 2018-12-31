<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$id = cleantext($_POST['id']);
$id_name = cleantext($_POST['id_name']); 
$name = cleantext($_POST['name']);
$data = cleantext($_POST['val']);
$table = cleantext($_POST['table']);

$stmt = $conn -> prepare("UPDATE {$table} SET 
											{$name} = :name_data
											WHERE {$id_name}  = :id_data");

$stmt -> bindValue(':name_data', $data);
$stmt -> bindValue(':id_data', $id);

$stmt -> execute();

?>
