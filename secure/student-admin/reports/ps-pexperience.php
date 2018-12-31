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
		<h2>Previous Experience - 
			<?php 
				if ($session_id != "") {print $session_details['session_program_code'].$session_details['session_number'];}
				if ($program_id != "") {print $program_details['program_code']." (All Sessions)";}
			?>
		</h2>
	</div>
  <table align="center">
  	<tr>
  		<th class="left" width="14%">Last Name</th>
  		<th class="left" width="14%">First Name</th>
  		<th width="8%">OE</th>
  		<th width="8%">GAP</th>
  		<th width="8%">Q</th>
  		<th width="8%">OR</th>
  		<th width="8%">OP</th>
  		<th width="8%">OS</th>
  		<th width="8%">SOLO</th>
  		<th width="8%">WIC</th>
  		<th width="8%">KIC</th>
  	</tr> 	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td  class=\"left\">".$student['student_name_last']."</td>";
  			 print "<td class=\"left\">".$student['student_name_first']."</td>";
  			 print "<td>".$student['completed_oe_year']."</td>";
  			 print "<td>".$student['completed_gap_year']."</td>";
  			 print "<td>".$student['completed_q_year']."</td>";
  			 print "<td>".$student['completed_outreach_year']."</td>";
  			 print "<td>".$student['completed_op_year']."</td>";
  			 print "<td>".$student['completed_os_year']."</td>";
  			 print "<td>".$student['completed_solo_year']."</td>";
  			 print "<td>".$student['completed_wic_year']."</td>";
  			 print "<td>".$student['completed_kic_year']."</td>";
				 $count++;
			} ?>
 	</table>
</body>
</html>
