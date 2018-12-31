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
	
	if (isset($_GET['s'])){$session = cleantext($_GET['s']);}
	else {$session = "";}

	$stmt = $conn->prepare("SELECT * FROM ss_sessions 
							LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
							WHERE session_id = :session_id");
	$stmt->bindValue(':session_id', $session);
	$stmt->execute();						
	$session_details = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn->prepare("SELECT * FROM ss_registrations
							WHERE accepted_session = :accepted_session
							ORDER BY student_name_last, student_name_first");
	$stmt->bindValue(':accepted_session', $session);
	$stmt->execute();						
	$student_results = $stmt->fetchAll();
?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
		<h2>Outreach Attendance - <?php print $session_details['session_program_code'].$session_details['session_number'];?></h2>
	</div>
  <table align="center">
  	<tr>
  		<th class="left" width="20%">Last Name</th>
  		<th class="left" width="20%">First Name</th>
  		<th width="10%">T-Shirt</th>
  		<th width="10%">Swim Test</th>
  		<th width="5%">Day 1</th>
  		<th width="5%">Day 2</th>
  		<th width="5%">Day 3</th>
  		<th width="5%">Day 4</th>
  		<th width="5%">Day 5</th>
  		<th width="5%">Day 6</th>
  		<th width="5%">Day 7</th>
  		<th width="5%">Trip</th>
  	</tr> 	
  		<?php
  		$count = 1;
  		foreach ($student_results as $student)
			{
  			 print "<tr><td  class=\"left\">".$student['student_name_last']."</td>";
  			 print "<td class=\"left\">".$student['student_name_first']."</td>";
			 print "<td class=\"center\">".$student['student_shirtsize']."</td>";
  			 print "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
				 $count++;
			} ?>
 	</table>
</body>
</html>
