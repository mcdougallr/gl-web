<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include('.../.../shared/clean.php');
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
		<h2>Medical Details - 
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
  		<th class="left">Medical Details</th>
  	</tr> 	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td>".$count."</td>";
  			 print "<td class=\"left\">".$student['student_name_last']."</td>";
  			 print "<td class=\"left\">".$student['student_name_first']."</td>";
  			 print "<td class=\"left\">";
  			 if ($student['student_health_hospitalized_details'] != ""){print "<em>Hospital: </em>".$student['student_health_hospitalized_details']."<br />";}
  			 if ($student['student_health_meds_details'] != ""){print "<em>Medications: </em>".$student['student_health_meds_details']."<br />";}
  			 if ($student['student_health_allergies_details'] != ""){print "<em>Allergies: </em>".$student['student_health_allergies_details']."<br />";}
	 			 if ($student['student_health_asthma_details'] != ""){print "<em>Asthma: </em>".$student['student_health_asthma_details']."<br />";}
	 			 if ($student['student_health_epipen_details'] != ""){print "<em>Epipen: </em>".$student['student_health_epipen_details']."<br />";}
	 			 if ($student['student_health_epilepsy_details'] != ""){print "<em>Epilepsy: </em>".$student['student_health_epilepsy_details']."<br />";}
	 			 if ($student['student_health_diabetes_details'] != ""){print "<em>Diabetes: </em>".$student['student_health_diabetes_details']."<br />";}
	 			 if ($student['student_health_counselor_details'] != ""){print "<em>Counselor: </em>".$student['student_health_counselor_details']."<br />";}
	 			 if ($student['student_health_anxiety_details'] != ""){print "<em>Anxiety: </em>".$student['student_health_anxiety_details']."<br />";}
 	 			 if ($student['student_health_limitations_details'] != ""){print "<em>Limitations: </em>".$student['student_health_limitations_details']."<br />";}
 	 			 if ($student['student_health_injuries_details'] != ""){print "<em>Injuries: </em>".$student['student_health_injuries_details']."<br />";}
 	 			 if ($student['student_health_others_details'] != ""){print "<em>Other: </em>".$student['student_health_others_details']."<br />";}
 	 			 print "</td></tr>";
				 $count++;
			} ?>
 	</table>
</body>
</html>
