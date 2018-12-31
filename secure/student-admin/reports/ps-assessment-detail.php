<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Detailed Assessment Report</title>

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
	}
</style>

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
	}s

?>
</head>
<body>
	<div style="text-align: center;">
		<h1 style="display: inline;">Gould Lake Outdoor Centre</h1>
	</div>
	<?php
		if (!empty($student_results))
		{
			//Print Non-Coop Marks
			?>
			<div style="text-align: center;">
				<h2>Detailed Assessment Report - 
					<?php 
					if ($session_id != "") {print $session_details['session_program_code'].$session_details['session_number'];}
					if ($program_id != "") {print $program_details['program_name'];}
					?>
				</h2>
			</div>
		  <table align="center">
			<tr>
                    <th>#</th>
                    <th class="left">Student</th>
                    <?php
                   		$stmt = $conn->prepare("SELECT * FROM `ss_assess`
												WHERE assess_program_id = :assess_program_id AND assess_coop = 'N'
												ORDER BY assess_sort");
						$stmt->bindValue(':assess_program_id', $session_details['program_id']);
						$stmt->execute();
						$assessments = $stmt->fetchAll();
												
						foreach ($assessments as $assessment){print "<th>".$assessment['assess_title']."</th>";}
					?>
                    
                    <th>Grade</th>
                    <th>Resp</th>
                    <th>Org</th>
                    <th>Indep Work</th>
                    <th>Collab</th>
                    <th>Init</th>
                    <th>Self-Reg</th>
			</tr> 	
				<?php
				$count = 1;	
				$totalforave = 0;
				
				foreach ($student_results as $student)
					{
						$reweight = 0;
						$totalmark = 0;
						$colcount = 1;
						print "<tr";
						if ($student['admin_assess_complete'] == 0) {print " style=\"background: #DDD;\"";}
						print "><td>".$count."</td><td class=\"left\">".$student['student_name_last'].", ".$student['student_name_first']."</td>";
					 
						/* if ($student['admin_assess_complete'] == 0)
						{
							print "<td colspan=7>Incomplete</td>";
						}
						else
						{*/
							//Calculate final mark
							$stmt = $conn->prepare("SELECT * FROM `ss_assess`
													WHERE assess_program_id = :assess_program_id AND assess_coop = 'N'
													ORDER BY assess_sort");
							$stmt->bindValue(':assess_program_id', $session_details['program_id']);
							$stmt->execute();
								$assessments = $stmt->fetchAll();
													
							foreach ($assessments as $assessment)
						{
								$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
														WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
							$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
							$stmt->bindValue(':data_student_id', $student['registration_id']);
							$stmt->execute();
							$mark = $stmt->fetch(PDO::FETCH_ASSOC);
								if ($mark['data_value'] != ""){print "<td>".$mark['data_value']."</td>";}
								else {print "<td>I</td>";}
								
								if ($assessment['assess_type'] == "L")
								{
									$stmt = $conn->prepare("SELECT * FROM `ss_assess_value`
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
									$colcount = $colcount + 1;
								}
								else 
								{
									$sectionmark = $mark['data_value'] / $assessment['assess_test_mark'] * $assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
									$colcount = $colcount + 1;
								}
							}
							$finalmark = ($totalmark / (100 - $reweight)) * 100;
							print "<td>".ceil($finalmark)."</td>";
							$totalforave = $totalforave + ceil($finalmark);
							
							//Print Learning Skills
							$stmt = $conn->prepare("SELECT * FROM `ss_assess`
													WHERE assess_sum = 'LS' AND assess_coop = 'N'
													ORDER BY assess_sort");
						$stmt->execute();
							$assessments = $stmt->fetchAll();
							
							foreach ($assessments as $assessment)
							{
									$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
															WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
								$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
								$stmt->bindValue(':data_student_id', $student['registration_id']);
								$stmt->execute();
								$mark = $stmt->fetch(PDO::FETCH_ASSOC);
				
									print "<td>".$mark['data_value']."</td>";
							}
						/*}*/
						print "</tr>";
						$count ++;
					}
					?>	
					<tr>
                    	<th></th>
                    	<th style="text-align: left;" colspan="<?php print $colcount; ?>">Average</th>
                    	<th><?php print $totalforave / ($count - 1); ?></th>
                    	<th colspan=6></th>
                   </tr>
			</table>
		<?php 
		} 
		
		//Print Coop Marks
		
		if ($session_id == 15 OR $session_id == 23)
		{
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

			$count = 1;
			$totalforave = 0;
			if (!empty($student_results))
			{
				?>
				<hr />
				<div style="text-align: center;">
					<h2>Assessment Report - 
						<?php print $session_details['program_name']." ".$session_details['session_number']." (Credit 2)"; ?>
					</h2>
				</div>
				  <table align="center">
					<tr>
	                    <th>#</th>
	                    <th class="left">Student</th>
	                    <?php
	                   		$stmt = $conn->prepare("SELECT * FROM `ss_assess`
													WHERE assess_program_id = :assess_program_id AND assess_coop = 'Y'
													ORDER BY assess_sort");
							$stmt->bindValue(':assess_program_id', $session_details['program_id']);
							$stmt->execute();
							$assessments = $stmt->fetchAll();
													
							foreach ($assessments as $assessment){print "<th>".$assessment['assess_title']."</th>";}
						?>   
	                    <th>Grade</th>
	                    <th>Resp</th>
	                    <th>Org</th>
	                    <th>Indep Work</th>
	                    <th>Collab</th>
	                    <th>Init</th>
	                    <th>Self-Reg</th>
					</tr> 	
					<?php
						
					foreach ($student_results as $student)
					{
							if ($session_id == 15 AND $student['admin_coop'] == 1 OR $session_id == 23)
							{
								$reweight = 0;
								$totalmark = 0;
								$colcount = 1;
								print "<tr";
								if ($student['admin_assess_complete'] == 0) {print " style=\"background: #DDD;\"";}
								print "><td>".$count."</td><td class=\"left\">".$student['student_name_last'].", ".$student['student_name_first']."</td>";

								//Calculate final mark
								$stmt = $conn->prepare("SELECT * FROM `ss_assess`
														WHERE assess_program_id = :assess_program_id AND assess_coop = 'Y'
														ORDER BY assess_sort");
								$stmt->bindValue(':assess_program_id', $session_details['program_id']);
								$stmt->execute();
								$assessments = $stmt->fetchAll();
													
								foreach ($assessments as $assessment)
								{
									$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
															WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
									$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
									$stmt->bindValue(':data_student_id', $student['registration_id']);
									$stmt->execute();
									$mark = $stmt->fetch(PDO::FETCH_ASSOC);
									if ($mark['data_value'] != ""){print "<td>".$mark['data_value']."</td>";}
									else {print "<td>I</td>";}
									if ($assessment['assess_type'] == "L")
									{
										$stmt = $conn->prepare("SELECT * FROM `ss_assess_value`
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
										$colcount = $colcount + 1;
									}
									else 
									{
										$sectionmark = $mark['data_value'] / $assessment['assess_test_mark'] * $assessment['assess_weight'];
										$totalmark = $totalmark + $sectionmark;
										if ($mark['data_value'] == "")
										{
											$reweight = $reweight + $assessment['assess_weight'];
										}
										$colcount = $colcount + 1;
									}
								}
								if ($reweight == 100){$finalmark = 0;}
								else {$finalmark = ($totalmark / (100 - $reweight)) * 100;}
								print "<td>".ceil($finalmark)."</td>";
								$totalforave = $totalforave + ceil($finalmark);
								
								//Print Learning Skills
								$stmt = $conn->prepare("SELECT * FROM `ss_assess`
														WHERE assess_sum = 'LS' AND assess_coop = 'Y'
														ORDER BY assess_sort");
								$stmt->execute();
								$assessments = $stmt->fetchAll();
							
								foreach ($assessments as $assessment)
								{
										$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
									$stmt->bindValue(':data_assess_id', $assessment['assess_id']);
									$stmt->bindValue(':data_student_id', $student['registration_id']);
									$stmt->execute();
									$mark = $stmt->fetch(PDO::FETCH_ASSOC);
					
										print "<td>".$mark['data_value']."</td>";
								}
								/*}*/
								print "</tr>";
								$count ++;
							}
						}
					?>	
					<tr>
                    	<th></th>
                    	<th style="text-align: left;" colspan="<?php print $colcount; ?>">Average</th>
                    	<th><?php print $totalforave / ($count - 1); ?></th>
                    	<th colspan=6></th>
                   </tr>
				</table>
			<?php 
			} 
		}?>
</body>
</html>
