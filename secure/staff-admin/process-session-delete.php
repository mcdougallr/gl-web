<?php

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$sd_id = cleantext($_POST['val']);
$stmt = $conn -> prepare("DELETE FROM gl_staff_session_days WHERE sd_id = :sd_id");
$stmt -> bindValue(':sd_id', $sd_id);
$stmt -> execute();