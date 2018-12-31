<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
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
		<h2>Course List - 
			<?php 
				if ($session_id != "") {print $session_details['session_program_code'].$session_details['session_number'];}
				if ($program_id != "") {print $program_details['program_name'];}
			?>
		</h2>
	</div>
  <table align="center">
  	<tr>
  		<th>#</th>
  		<th class="left">Name</th>
  		<th>Gender</th>
  		<th>DOB</th>
  		<th>OEN</th>
        <th>Home Phone</th>
        <th>Email</th>
        <th>LDSB?</th>
  	</tr>
  	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td>".$count."</td>";
  			 print "<td class=\"left\">".$student['student_name_last'].", ".$student['student_name_first']." ";
			 if ($student['confirm_photo'] == "N") {print "<i class=\"fa fa-camera\"></i>";}
			 if ($student['confirm_social_media'] == "N") {print "<i class=\"fa fa-twitter\"></i>";}
			 if ($student['admin_coop'] == 1) {print "<i class=\"fa fa-bug\"></i>";}
			 print "</td>";
  			 print "<td>".$student['student_sex']."</td>";
  			 print "<td>".$student['student_dob']."</td>";
			 print "<td>".$student['student_oen']."</td>";
			 print "<td>".$student['g1_p1']."</td>";
				 print "<td>".$student['contact_email']."</td>";
				 print "<td>";
				 if ($student['admin_non_ldsb'] == 0){print "Yes";}else{print "No";}
				 print "</td>";
				 print "</tr>";
				 $count++;
			} ?>
 	</table>
</body>
</html>
