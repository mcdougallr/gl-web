<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$field_name = cleantext($_POST['field_name']);
$field_val = cleantext($_POST['field_val']);
$table = cleantext($_POST['table_name']);
$id_name = cleantext($_POST['id_name']); 
$id_val = cleantext($_POST['id_val']);

if ($field_name == "bus_run_return_time" AND $field_val == "") {$field_val = NULL;}

$stmt = $conn -> prepare("UPDATE {$table} SET 
							{$field_name} = :field_val
							WHERE {$id_name}  = :id_val");

$stmt -> bindValue(':field_val', $field_val);
$stmt -> bindValue(':id_val', $id_val);
$stmt -> execute();

?>
