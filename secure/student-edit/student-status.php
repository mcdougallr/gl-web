<?php 
	session_start();
	include ('../shared/dbconnect.php');

	$stmt = $conn -> prepare("SELECT accepted_session, status_email_sent_accept, status_accept_read, status_accept_confirmed, waitlist 
								FROM ss_registrations WHERE registration_id = :registration_id");
	$stmt -> bindValue(':registration_id', $_SESSION['student_id']);
	$stmt -> execute();
	$student = $stmt -> fetch(PDO::FETCH_ASSOC);	

	$stmt = $conn->prepare("SELECT session_program_code, session_number FROM ss_sessions
							WHERE session_id = :session_id");
	$stmt->bindValue(':session_id', $student['accepted_session']);
	$stmt->execute();
	$session = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$status="";
	if ($student['accepted_session']!="0"){$status = "Secured: ";} else {$status = "Registered...";}
	if ($student['status_email_sent_accept']=="1"){$status = "Emailed: ";}
	if ($student['status_accept_read']=="1"){$status = "Read Email: ";}
	if ($student['status_accept_confirmed']=="1"){$status = "Confirmed: ";}
	
	print $status.$session['session_program_code'].$session['session_number'];
	if ($student['waitlist'] != "") {print "(WL)";}

?>