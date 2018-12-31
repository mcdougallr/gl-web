<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$file_id = cleantext($_POST['val']);

$stmt = $conn -> prepare("SELECT file_name FROM staff_files
											WHERE file_id = :file_id");
$stmt -> bindValue(':file_id', $file_id);
$stmt -> execute();
$file = $stmt -> fetch(PDO::FETCH_ASSOC);

$name = "docs/".$file['file_name'];

unlink($name);

$stmt = $conn -> prepare("DELETE FROM staff_files WHERE file_id = :file_id");
$stmt -> bindValue(':file_id', $file_id);
$stmt -> execute();