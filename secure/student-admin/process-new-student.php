<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');
include ('../shared/functions.php');


$date = date("Y-m-d");
$time = date("H:i:s");

//CREATE UNIQUE REGISTRATION CODE
if (!isset($_SESSION['registration_code']))
{
	$_SESSION['registration_code'] = generatePassword();
	for ($i=0; $i<1; $i)
	{
		$stmt = $conn->prepare("SELECT * FROM ss_registrations WHERE registration_code = :regcode");
		$stmt -> bindValue(':regcode', $_SESSION['registration_code']);
		$stmt -> execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($result))
		{
			$_SESSION['registration_code'] = generatePassword();
		}
		else
		{$i=1;}
	}
}

$stmt = $conn -> prepare("INSERT INTO ss_registrations
													(registration_code, registration_date, registration_time) 
													VALUES
													(:registration_code, :registration_date, :registration_time) ");

$stmt -> bindValue(':registration_date', $date);
$stmt -> bindValue(':registration_time', $time);
$stmt -> bindValue(':registration_code', $_SESSION['registration_code']);
$stmt -> execute();

$sid = $conn -> lastInsertId();

header("Location: ../student-edit/index.php?sid=".$sid);

?>
