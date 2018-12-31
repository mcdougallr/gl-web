<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<title>No Pic or Twitter Report</title>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/authenticate.php');

	$studentquery = "SELECT * FROM ss_registrations
					LEFT JOIN ss_sessions ON ss_registrations.accepted_session = ss_sessions.session_id 
					WHERE accepted_session != 0 AND confirm_social_media = \"N\" OR accepted_session != 0 AND confirm_photo = \"N\"
					ORDER BY session_sortorder, student_name_last, student_name_first";
	
?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
		<h2>No Picture and Twitter List</h2>
	</div>
  <table align="center">
  	<tr>
  		<th class="left">Course</th>
        <th class="left">Last Name</th>
  		<th class="left">First Name</th>
        <th class="center"><i class="fa fa-camera"></i></th>
        <th class="center"><i class="fa fa-twitter"></i></th>
  	</tr> 	
  		<?php
  		foreach ($conn->query($studentquery) as $student)
			{
  			 print "<td class=\"left\">".$student['session_program_code'].$student['session_number']."</td>";
			 print "<td class=\"left\">".$student['student_name_last']."</td>";
  			 print "<td class=\"left\">".$student['student_name_first']."</td><td>";
  			 if ($student['confirm_photo'] == "N") {print "<i class=\"fa fa-camera\"></i>";}
			 print "</td><td>";
			 if ($student['confirm_social_media'] == "N") {print "<i class=\"fa fa-twitter\"></i>";}
			 print "</td></tr>";
			} ?>
 	</table>
</body>
</html>
