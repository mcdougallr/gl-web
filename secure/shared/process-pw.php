<?php
session_start();
include ('dbconnect.php');

if (isset($_POST['staff_login'])) {$staff_login = $_POST['staff_login'];}
if (isset($_POST['staff_password'])) {$staff_password = $_POST['staff_password'];}

if(isset($staff_login) and isset($staff_password)) 
{
	$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_email = :staff_login AND staff_password = :staff_password");

	/*** bind the paramaters ***/
	$stmt->bindParam(':staff_login', $staff_login, PDO::PARAM_STR, 30);
	$stmt->bindParam(':staff_password', $staff_password, PDO::PARAM_STR, 20);

	/*** execute the prepared statement ***/
	$stmt->execute();

	/*** fetch the results ***/
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!empty($result))
	{
		$_SESSION['logged_in'] = 1;
		$_SESSION['gl_staff_id'] = $result['staff_id'];
		$_SESSION['staff_access'] = $result['staff_access'];
	}
}

if (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == 1) {$message['login'] = 1;}
else {$message['login'] = 0;}
if (isset($_SESSION['gl_staff_id'])) {$message['staff_id'] = $_SESSION['gl_staff_id'];}
else {$message['staff_id'] = "";}
echo json_encode($message);