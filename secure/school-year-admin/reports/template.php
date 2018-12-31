<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	
	if (isset($_GET['p'])){$program = cleantext($_GET['p']);}
	else {$program = "";}
	if (isset($_GET['s'])){$session = cleantext($_GET['s']);}
	else {$session = "";}
	
	$stmt = $conn->prepare("SELECT * FROM gl_programs
													WHERE program_id = :program_id");
	$stmt->bindValue(':program_id', $program);
	$stmt->execute();
	$program_results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($program == "All")
	{
		$stmt = $conn->prepare("SELECT * FROM gl_registrations
														LEFT JOIN gl_sessions ON gl_registrations.accepted_session = gl_sessions.session_id
														LEFT JOIN gl_programs ON gl_programs.program_code = gl_sessions.session_program_code
														WHERE admin_non_ldsb = 1 AND accepted_session != ''
														ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':program_id', $program);
		$stmt->execute();						
		$student_results = $stmt->fetchAll();
		$program = $program_results['program_code'];
		$session = "All Students";
	}
	elseif ($session == "All")
	{
		$stmt = $conn->prepare("SELECT * FROM gl_registrations
														LEFT JOIN gl_sessions ON gl_registrations.accepted_session = gl_sessions.session_id
														LEFT JOIN gl_programs ON gl_programs.program_code = gl_sessions.session_program_code
														WHERE program_id = :program_id  AND accepted_session != ''
														ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':program_id', $program);
		$stmt->execute();						
		$student_results = $stmt->fetchAll();
		$program = "All Students";
		$session = "";
	}
	else 
	{
		$stmt = $conn->prepare("SELECT * FROM gl_registrations
														LEFT JOIN gl_sessions ON gl_registrations.accepted_session = gl_sessions.session_id
														LEFT JOIN gl_programs ON gl_programs.program_code = gl_sessions.session_program_code
														WHERE session_number = :session_number AND program_id = :program_id AND accepted_session != ''
														ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':session_number', $session);
		$stmt->bindValue(':program_id', $program);
		$stmt->execute();						
		$student_results = $stmt->fetchAll();
	}

	if (count($student_results) == 0){print "<script>alert(\"This session is either empty or doesn't exist!\");</script>";}
	
?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
	</div>
  <table>
  	<tr>
  		<td>
  			
  		</td>
  	</tr>
 	</table>
</body>
</html>
