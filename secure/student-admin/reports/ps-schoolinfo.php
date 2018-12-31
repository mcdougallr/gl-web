<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
	
	$session_id = "";
	$program_id = "";
	
	if (isset($_GET['s']))
	{
		$session_id = cleantext($_GET['s']);
		$stmt = $conn->prepare("SELECT * FROM ss_sessions 
								LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
								WHERE session_id = :session_id");
		$stmt->bindValue(':session_id', $session_id);
		$stmt->execute();						
		$session_details = $stmt->fetch(PDO::FETCH_ASSOC);
	
		$stmt = $conn->prepare("SELECT * FROM ss_registrations
								WHERE accepted_session = :accepted_session
								ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':accepted_session', $session_id);
		$stmt->execute();						
		$student_results = $stmt->fetchAll();
	}
		
	if (isset($_GET['p']))
	{
		$program_id = cleantext($_GET['p']);
		$stmt = $conn->prepare("SELECT * FROM ss_programs
								WHERE program_id = :program_id");
		$stmt->bindValue(':program_id', $program_id);
		$stmt->execute();						
		$program_details = $stmt->fetch(PDO::FETCH_ASSOC);
	
		$stmt = $conn->prepare("SELECT * FROM ss_registrations
								WHERE selected_program = :selected_program AND accepted_session != 0
								ORDER BY student_name_last, student_name_first");
		$stmt->bindValue(':selected_program', $program_id);
		$stmt->execute();						
		$student_results = $stmt->fetchAll();
	}
	
?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
		<h2>School Info - 
			<?php 
				if ($session_id != "") {print $session_details['session_program_code'].$session_details['session_number'];}
				if ($program_id != "") {print $program_details['program_code']." (All Sessions)";}
			?>
		</h2>
	</div>
  <table align="center">
  	<tr>
  		<th>#</th>
  		<th class="left">Last Name</th>
  		<th class="left">First Name</th>
  		<th>Gender</th>
        <th>OEN</th>
  		<th>LDSB?</th>
  		<th>Fall School</th>
  	</tr> 	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td>".$count."</td>";
  			 print "<td class=\"left\">".$student['student_name_last']."</td>";
  			 print "<td class=\"left\">".$student['student_name_first']."</td>";
  			 print "<td>".$student['student_sex']."</td>";
  			 print "<td>".$student['student_oen']."</td>";
				 print "<td>";
				 if ($student['admin_non_ldsb'] == 0){print "Yes";}else{print "No";}
				 print "</td>";
				 print "<td>".$student['student_school_fall']."</td></tr>";
				 $count++;
			} ?>
 	</table>
</body>
</html>
