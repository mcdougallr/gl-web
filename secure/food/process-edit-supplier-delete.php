<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$supplier_id = cleantext($_POST['supplier_id']);

$stmt = $conn -> prepare("DELETE FROM fd_suppliers WHERE supplier_id = :supplier_id");   
$stmt -> bindValue(':supplier_id', $_POST['supplier_id']);	   
$stmt -> execute();

?>
