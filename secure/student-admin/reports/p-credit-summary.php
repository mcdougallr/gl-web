<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../../../scripts/pure/pure-min.css">  
<title>Assessment Report</title>

<style>
	h1 {
		font-family: FTYS;
	}
	h2 {
		font-size: 1em;
	}
	th {
		font-size: .9em;
	}
	td {
		font-size: .8em;
		padding: 2px;
	}
</style>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/authenticate.php');

	$stmt = $conn->prepare("CREATE TEMPORARY TABLE credit_totals
							SELECT registration_id, 
								   student_name_last, 
								   student_name_first,
								   admin_coop,
								   admin_coop_yes,
								   program_credit
							FROM ss_registrations
							INNER JOIN ss_sessions ON ss_sessions.session_id = ss_registrations.accepted_session
							INNER JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
							WHERE accepted_session != 0 AND program_credit != '-'");
	$stmt->execute();	
	
	$stmt = $conn->prepare("SELECT * FROM ss_registrations
							WHERE admin_credit != ''");
	$stmt->execute();	
	$student_results = $stmt->fetchAll();	
	
	foreach ($student_results as $student)
	{
		$stmt = $conn->prepare("UPDATE credit_totals
							   SET program_credit = :program_credit
							   WHERE registration_id = :registration_id");
		$stmt->bindValue(':program_credit', $student['admin_credit']);
		$stmt->bindValue(':registration_id', $student['registration_id']);
		$stmt->execute();
	}
	
	$stmt = $conn->prepare("SELECT * FROM credit_totals
							
							ORDER BY program_credit,student_name_last, student_name_first");
	$stmt->execute();	
	$credit_results = $stmt->fetchAll();

?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
		<img src="sunman-black.png" width="30"/>
	</div>
	<?php
	if (!empty($credit_results))
	{
		
		$new = "";
		$old = "";
		$count = 0;
		$first = 1;
		?>
		<div style="text-align: center;">
			<h2>Final Credit Tallies</h2>
		</div>
		<div class="pure-g">
		<?php 
		foreach ($credit_results as $student)
		{
			$new = $student['program_credit'];
			if ($new != $old)
			{
				if ($first == 1)
				{
					print "<div class=\"pure-u-1\" style=\"border: 1px #FFF solid;\"><h3 style=\"text-align: left;\">".$new."</h3><table align=left width=60%>";
					$count = 1;
					$first = 0;
				}
				else {
					print "</table></div><div class=\"pure-u-1\" style=\"border: 1px #FFF solid;\"><h3 style=\"text-align: left;\">".$new."</h3><table align=left width=60%>";
					$count = 1;
				}
			}
			print "<tr><td width=20%>".$count."</td><td width=40%>".$student['student_name_last']."</td><td width=40%>".$student['student_name_first'];
			if ($student['admin_coop'] == 1) {print "*";}
			print "</td></tr>";
			$old = $new;
			$count ++;
		}
		print "</table>";
	}
	?>
	</div>
	<?php		/*	
				$count = 0;	
				foreach ($student_results as $student)
					{
						$reweight = 0;
						$totalmark = 0;
						print "<tr><td>".$count."</td><td class=\"left\">".$student['student_name_last'].", ".$student['student_name_first']."</td>";
					 
						if ($student['admin_assess_complete'] == 0)
						{
							print "<td colspan=7>Incomplete</td>";
						}
						else
						{
							//Calculate final mark
							$stmt = $conn->prepare("SELECT * FROM `gl_assess`
																			WHERE assess_program_id = :assess_program_id AND assess_coop = 'N'
																			ORDER BY assess_sort");
							$stmt->bindValue(':assess_program_id', $session_details['program_id']);
							$stmt->execute();
								$assessments = $stmt->fetchAll();
													
							foreach ($assessments as $assessment)
						{
								$stmt = $conn->prepare("SELECT * FROM `gl_assess_data`
																				WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
							$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
							$stmt->bindValue(':data_student_id', $student['registration_id']);
							$stmt->execute();
							$mark = $stmt->fetch(PDO::FETCH_ASSOC);
								
								if ($assessment['assess_type'] == "L")
								{
									$stmt = $conn->prepare("SELECT * FROM `gl_assess_value`
																					WHERE value_level = :value_level");
								$stmt->bindValue(':value_level', $mark['data_value']);
								$stmt->execute();
								$value = $stmt->fetch(PDO::FETCH_ASSOC);	
										
									$sectionmark = $value['value_percent']*$assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
								}
								else 
								{
									$sectionmark = $mark['data_value'] / $assessment['assess_test_mark'] * $assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
								}
							}
							$finalmark = ($totalmark / (100 - $reweight)) * 100;
							print "<td>".ceil($finalmark)."</td>";
							
							//Print Learning Skills
							$stmt = $conn->prepare("SELECT * FROM `gl_assess`
																				WHERE assess_sum = 'LS' AND assess_coop = 'N'
																				ORDER BY assess_sort");
						$stmt->execute();
							$assessments = $stmt->fetchAll();
							
							foreach ($assessments as $assessment)
							{
									$stmt = $conn->prepare("SELECT * FROM `gl_assess_data`
															WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
								$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
								$stmt->bindValue(':data_student_id', $student['registration_id']);
								$stmt->execute();
								$mark = $stmt->fetch(PDO::FETCH_ASSOC);
				
									print "<td>".$mark['data_value']."</td>";
							}
						}
						print "<td>".$student['admin_credit']."</td>";
						print "<td>".$student['admin_absent']."</td>";
						print "</tr>";
						$count ++;
					}
					?>	
			</table>
		<?php 
		} 
		
		//Print Coop Marks
		if ($session_details['program_id'] == 6)
		{
			$stmt = $conn->prepare("SELECT * FROM ss_registrations
															WHERE accepted_session = :accepted_session AND admin_coop_yes = 1
															ORDER BY student_name_last, student_name_first");
			$stmt->bindValue(':accepted_session', $session_details['session_id']);
			$stmt->execute();						
			$student_results = $stmt->fetchAll();
			
			$count = 0;
			if (!empty($student_results))
			{
				?>
				<div style="text-align: center;">
					<h2>Assessment Report - 
						<?php print $session_details['program_name']." ".$session_details['session_number']." (Coop)"; ?>
					</h2>
				</div>
			  <table align="center">
				<tr>
                    <th class="left" width="35%">Student</th>
                    <th width="5%">Grade</th>
                    <th width="5%">Resp</th>
                    <th width="5%">Org</th>
                    <th width="5%">Indep Work</th>
                    <th width="5%">Collab</th>
                    <th width="5%">Init</th>
                    <th width="5%">Self-Reg</th>
                    <th width="15%">Alt Credit Code</th>
                    <th width="15%">Absence Notes</th>
				</tr> 	
					<?php
						
					foreach ($student_results as $student)
						{
							$totalmark = 0;
							print "<tr><td>".$count."</td><td class=\"left\">".$student['student_name_last'].", ".$student['student_name_first']."</td>";
						 
						 if ($student['admin_assess_complete'] == 0)
						{
							print "<td colspan=7>Incomplete</td>";
						}
						else
						{
							//Calculate final mark
							
								$stmt = $conn->prepare("SELECT * FROM `gl_assess`
																				WHERE assess_program_id = '6' AND assess_coop = 'Y'
																				ORDER BY assess_sort");
								$stmt->execute();
									$assessments = $stmt->fetchAll();
								
								
								foreach ($assessments as $assessment)
							{
									$stmt = $conn->prepare("SELECT * FROM `gl_assess_data`
															WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
								$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
								$stmt->bindValue(':data_student_id', $student['registration_id']);
								$stmt->execute();
								$mark = $stmt->fetch(PDO::FETCH_ASSOC);
									
									if ($assessment['assess_type'] == "L")
									{
										$stmt = $conn->prepare("SELECT * FROM `gl_assess_value`
																						WHERE value_level = :value_level");
									$stmt->bindValue(':value_level', $mark['data_value']);
									$stmt->execute();
									$value = $stmt->fetch(PDO::FETCH_ASSOC);	
											
										$sectionmark = $value['value_percent']*$assessment['assess_weight'];
										$totalmark = $totalmark + $sectionmark;
									}
									else 
									{
										$sectionmark = $mark['data_value'] / $assessment['assess_test_mark'] * $assessment['assess_weight'];
										$totalmark = $totalmark + $sectionmark;
									}
								}
								print "<td>".ceil($totalmark)."</td>";
								
								//Print Learning Skills
								$stmt = $conn->prepare("SELECT * FROM `gl_assess`
																				WHERE assess_sum = 'LS' AND assess_coop = 'Y'
																				ORDER BY assess_sort");
							$stmt->execute();
								$assessments = $stmt->fetchAll();
								
								foreach ($assessments as $assessment)
								{
										$stmt = $conn->prepare("SELECT * FROM `gl_assess_data`
																WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
									$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
									$stmt->bindValue(':data_student_id', $student['registration_id']);
									$stmt->execute();
									$mark = $stmt->fetch(PDO::FETCH_ASSOC);
					
										print "<td>".$mark['data_value']."</td>";
								}
							}
							print "<td>".$student['admin_credit']."</td>";
							print "<td>".$student['admin_absent']."</td>";
							print "</tr>";
							$count ++;
						}
						?>	
				</table>
			<?php 
			} 
		}*/?>
</body>
</html>
