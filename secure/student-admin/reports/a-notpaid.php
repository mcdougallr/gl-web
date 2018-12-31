<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Not Paid</title>
</head>
<body>
<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/authenticate.php');
?>

		<?php
		$sessionquery = "SELECT * FROM ss_sessions ORDER BY session_sortorder";
		foreach ($conn->query($sessionquery) as $sessions)
		{
			print "<h3>". $sessions['session_program_code']." ". $sessions['session_number']. "</h3>";

			$stmt = $conn->prepare("SELECT registration_id, student_name_last, student_name_first, admin_paid, student_email, g1_name_first, g1_name_last, g1_email, registration_date FROM ss_registrations
															LEFT JOIN ss_sessions ON ss_registrations.accepted_session=ss_sessions.session_id 
															WHERE accepted_session = :accepted_session AND admin_paid = 0														ORDER BY student_name_last, student_name_first");
			$stmt->bindValue(':accepted_session', $sessions['session_id']);
			$stmt->execute();
			$result = $stmt->fetchAll();
			?>

			<table width="100%" class="pure-table pure-table-bordered">
				<tr>
					<th width="20%">Student</th>
					<th width="20%">Student Email</th>
					<th align=center width="20%">Contact 1</th>
					<th align=center width="20%">Contact Email</th>
					<th align=center width="20%">Registration Date</th>
				</tr>
			
			<?php

			foreach ($result as $student)
	    { ?>
				<tr>
					<td style="text-align: left;">
						<?php print $student['student_name_last'] . ", ". $student['student_name_first']; ?>
					</td>
					<td>
						<?php print $student['student_email']; ?>
					</td>
					<td>
						<?php print $student['g1_name_first'] . " ". $student['g1_name_last']; ?>
					</td>
					<td>
						<?php print $student['g1_email']; ?>
					</td>
					<td>
						<?php print $student['registration_date']; ?>
					</td>
				</tr>
				<?php					
			}
			?>
			</table>
		<?php		
		}
		?>
	</table>
</body>
</html>
