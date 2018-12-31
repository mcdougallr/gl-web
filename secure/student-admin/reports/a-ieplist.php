<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IEP List</title>
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

			$stmt = $conn->prepare("SELECT registration_id, student_name_last, student_name_first, admin_if, student_learning_accommodations FROM ss_registrations
															LEFT JOIN ss_sessions ON ss_registrations.accepted_session=ss_sessions.session_id 
															WHERE accepted_session = :accepted_session AND student_learning_accommodations != \"\"
															ORDER BY student_name_last, student_name_first");
			$stmt->bindValue(':accepted_session', $sessions['session_id']);
			$stmt->execute();
			$result = $stmt->fetchAll();
			?>

			<table width="100%" class="pure-table pure-table-bordered">
				<tr>
					<th width="30%">Student</th>
					<th align=center width="60%">Learning Accomodations</th>
					<th align=center width="10%">Flagged</th>
				</tr>
			
			<?php

			foreach ($result as $student)
	    { ?>
				<tr>
					<td style="text-align: left;">
						<?php print $student['student_name_last'] . ", ". $student['student_name_first']; ?>
					</td>
					<td>
						<?php print $student['student_learning_accommodations']; ?>
					</td>
					<td>
						<?php 
							if ($student['admin_if'] == 1) {print "Y";}
							else {print "N";}
						?>
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
