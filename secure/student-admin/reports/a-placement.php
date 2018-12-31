<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Placement Info</title>
</head>
<body>
<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/authenticate.php');
?>

<table width="100%" class="pure-table pure-table-bordered">
	<tr>
		<th>Student</th>
		<th>Session</th>
		<th>Placement Info</th>
	</tr>
	<?php
	$studentquery = "SELECT registration_id, student_name_last, student_name_first, selected_placement, session_program_code FROM ss_registrations
								LEFT JOIN ss_sessions ON ss_registrations.accepted_session=ss_sessions.session_id 
								WHERE accepted_session != 0 AND selected_placement != ''
								ORDER BY session_program_code, student_name_last, student_name_first";
	foreach ($conn->query($studentquery) as $student)
	{ ?>
		<tr>
			<td style="text-align: left;">
				<?php print $student['student_name_last'] . ", ". $student['student_name_first']; ?>
			</td>
			<td>
				<?php print $student['session_program_code']; ?>
			</td>
			<td>
				<?php print $student['selected_placement']; ?>
			</td>
		</tr>
		<?php					
	}
	?>
</table>
</body>
</html>
