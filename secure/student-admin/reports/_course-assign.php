<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<title></title>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	
	if (isset($_GET['p'])){$program = cleantext($_GET['p']);}
	else {$program = "";}

	$stmt = $conn->prepare("SELECT * FROM gl_programs
													WHERE program_id = :program_id");
	$stmt->bindValue(':program_id', $program);
	$stmt->execute();						
	$program_details = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn->prepare("SELECT * FROM gl_registrations
													WHERE selected_program = :selected_program and registration_date > '2016-01-03' AND accepted_session = 0
													ORDER BY registration_date, registration_time, student_name_last, student_name_first");
	$stmt->bindValue(':selected_program', $program);
	$stmt->execute();						
	$student_results = $stmt->fetchAll();
	
?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
		<h2>Assign List - <?php print $program_details['program_name'];?></h2>
	</div>
  <table align="center">
  	<tr>
  		<th>#</th>
  		<th>#</th>
  		<th class="left">Name</th>
  		<th>Choice 1</th>
  		<th>Choice 2</th>
  		<th>School</th>
  		<th>Reg Date/Time</th>
  	</tr>
  	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td>".$count."</td>";print "<td>".$count."</td>";
  			 print "<td class=\"left\">".$student['student_name_last'].", ".$student['student_name_first'];
			 print "</td>";
  			 print "<td>";
  			 if ($student['selected_session1'] != "")
			 {
			 	$stmt = $conn->prepare("SELECT * FROM gl_sessions
													WHERE session_id = :session_id");
				$stmt->bindValue(':session_id', $student['selected_session1']);
				$stmt->execute();						
				$session_details = $stmt->fetch(PDO::FETCH_ASSOC);
				print $session_details['session_program_code'].$session_details['session_number'];
			 }
			 print "</td><td>";
			 if ($student['selected_session2'] != "")
			 {
			 	$stmt = $conn->prepare("SELECT * FROM gl_sessions
													WHERE session_id = :session_id");
				$stmt->bindValue(':session_id', $student['selected_session2']);
				$stmt->execute();						
				$session_details = $stmt->fetch(PDO::FETCH_ASSOC);
				print $session_details['session_program_code'].$session_details['session_number'];
			 }
			 print "</td>";
  			 print "<td>".$student['student_school_fall']."</td>";
			 print "<td>".$student['registration_date']." ".$student['registration_time']."</td>";
			 print "</tr>";
			 $count++;
			} ?>
 	</table>
</body>
</html>
