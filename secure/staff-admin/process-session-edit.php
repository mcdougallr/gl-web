<?php

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$session_id = cleantext($_POST['session_id']);
$name = cleantext($_POST['name']);
$data = cleantext($_POST[$name]);

$stmt = $conn -> prepare("UPDATE gl_sessions SET 
											{$name} = :name_data
											WHERE session_id = :session_id");

$stmt -> bindValue(':name_data', $data);
$stmt -> bindValue(':session_id', $session_id);

$stmt -> execute();

?>
