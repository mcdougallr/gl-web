<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SAF Report</title>

<style>
	h1 {
		font-family: FTYS;
	}
	h2 {
		font-variant: small-caps;
	}
	td, p, th {
		font-size: 12px;
	}
	table {
		width: 100%;
		margin: 0 auto;
	}
</style>

<?php
	session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
	
	if (isset($_GET['s'])){$session = cleantext($_GET['s']);}
	else {$session = "";}
	
	$stmt = $conn->prepare("SELECT * FROM ss_registrations
						   	LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_registrations.accepted_session
							LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code
							WHERE accepted_session = :accepted_session and admin_assess_complete = 1
							ORDER BY student_name_last, student_name_first");
	$stmt->bindValue(':accepted_session', $session);
	$stmt->execute();						
	$student_results = $stmt->fetchAll();
	
?>
</head>
<body>
	<?php
  	$studentcount = 0;	
	$first = 1;
	foreach ($student_results as $student)
	{
		$stmt = $conn->prepare("SELECT * FROM `ss_assess_credit`
								WHERE credit_program_id = :credit_program_id AND credit_coop = 'N'");
	    $stmt->bindValue(':credit_program_id', $student['program_id']);
	    $stmt->execute();
	    $credit = $stmt->fetch(PDO::FETCH_ASSOC);?>
		<?php 
			if ($studentcount == 0) 
			{
				if ($first == 1){print "<div class=\"pagebreak\">";$first = 0;}
				else {print "</div><div class=\"pagebreak\">";}
			}
			?>
				<div style="text-align: center;">
					<img src="lethead.png" width="100%"/>
				</div>
				<h1 style="font-size: 1.5em;font-weight: normal;">Student Achievement Form</h1>
			  <table style="border: none;">
			  	<tr>
			  		<td style="border: none;" class="left" width="50%"><span style="font-weight: bold">Student Name: </span><?php print $student['student_name_first']." ".$student['student_name_last'];?></td>
			  		<td style="border: none;" class="left" width="50%"><span style="font-weight: bold">OEN: </span><?php print $student['student_oen'];?></td>
			  	</tr>  	
			  	<tr>
			  		<td style="border: none;" class="left"><span style="font-weight: bold">Credit: </span>
			  			<?php 
			  				if ($student['admin_credit'] == "")
			  					{
			  						if ($session == 15) {print "PLF4M5";}
									else if ($session == 23) {print "PAD4O5";}
									else print $student['program_credit'];} 
			  				else {print $student['admin_credit'];}
			  			?>
			  		</td>
			  		<td style="border: none;" class="left"><span style="font-weight: bold">Credit Value: </span><?php print $student['program_credit_value'];?></td>
			  	</tr>	
            </table>
            <br />
            <table>
			  	<tr>
			  		<th width="14%">Mark</th>
			  		<th width="14%">Responsibility</th>
			  		<th width="14%">Organization</th>
			  		<th width="14%">Independent Work</th>
			  		<th width="14%">Collaboration</th>			  		
			  		<th width="14%">Initiative</th>
			  		<th width="14%">Self-Regulation</th>			  		
			  	</tr>  	
			  	<tr>
			  		<td>
							<?php 
							$finalmark = 0;
							$totalmark = 0;
							$reweight = 0;
								$stmt = $conn->prepare("SELECT * FROM `ss_assess`
														WHERE assess_program_id = :assess_program_id AND assess_coop = 'N'
														ORDER BY assess_sort");
			    $stmt->bindValue(':assess_program_id', $student['program_id']);
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
					print ceil($finalmark);
				?>
						</td>
						<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													LEFT JOIN ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'N' AND assess_title = 'Responsibility'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
			  		<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'N' AND assess_title = 'Organization'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
			  		<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																				left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																				WHERE data_student_id = :data_student_id AND assess_coop = 'N' AND assess_title = 'Independent Work'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
			  		<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																				LEFT JOIN ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																				WHERE data_student_id = :data_student_id AND assess_coop = 'N' AND assess_title = 'Collaboration'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
						
						<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																				left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																				WHERE data_student_id = :data_student_id AND assess_coop = 'N' AND assess_title = 'Initiative'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
						<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																				left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																				WHERE data_student_id = :data_student_id AND assess_coop = 'N' AND assess_title = 'Self-Regulation'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
					</tr>	
			 	</table>
     
	    <?php 
		if ($student['accepted_session'] == 15)
		{
			if ($student['admin_coop'] == 1)
			{
				$stmt = $conn->prepare("SELECT * FROM `ss_assess_credit`
															WHERE credit_program_id = :credit_program_id AND credit_coop = 'Y'");
			   $stmt->bindValue(':credit_program_id', $student['program_id']);
			   $stmt->execute();
			   $credit = $stmt->fetch(PDO::FETCH_ASSOC);
				?>
				  <br />
				  <br />
				  <table style="border: none;">
				  	<tr>
				  		<td style="border: none;" class="left"><span style="font-weight: bold">Credit: </span>
							<?php
								if ($student['admin_credit'] == "")
				  					{
				  						if ($session == 15) {print "PLF4MA";}
										else if ($session == 23) {print "GPP3O5";}
										else print $student['program_credit'];} 
				  				else {print $student['admin_credit'];}
				  			?>
			  			</td>
				  		<td style="border: none;" class="left"><span style="font-weight: bold">Credit Value: </span><?php print $student['program_credit_value'];?></td>
				  	</tr>	
	            </table>
	            <br />
	            <table>
				  	<tr>
				  		<th width="14%">Mark</th>
				  		<th width="14%">Responsibility</th>
				  		<th width="14%">Organization</th>
				  		<th width="14%">Independent Work</th>
				  		<th width="14%">Collaboration</th>			  		
				  		<th width="14%">Initiative</th>
				  		<th width="14%">Self-Regulation</th>			  		
				  	</tr>  	
				  	<tr>
				  		<td>
								<?php 
								$finalmark = 0;
								$totalmark = 0;
								$reweight = 0;
									$stmt = $conn->prepare("SELECT * FROM `ss_assess`
																		WHERE assess_program_id = :assess_program_id AND assess_coop = 'Y'
																		ORDER BY assess_sort");
				    $stmt->bindValue(':assess_program_id', $student['program_id']);
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
								
								
							$count ++;
						}
						$finalmark = ($totalmark / (100 - $reweight)) * 100;
						print ceil($finalmark);
					?>
							</td>
							<td>										
				  			<?php
				  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																					LEFT JOIN ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																					WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Responsibility'");
							    $stmt->bindValue(':data_student_id', $student['registration_id']);
							    $stmt->execute();
							    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
							    print $markvalue['data_value'];?>			  			
				  		</td>
				  		<td>										
				  			<?php
				  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																					left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																					WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Organization'");
							    $stmt->bindValue(':data_student_id', $student['registration_id']);
							    $stmt->execute();
							    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
							    print $markvalue['data_value'];?>			  			
				  		</td>
				  		<td>										
				  			<?php
				  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																					left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																					WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Independent Work'");
							    $stmt->bindValue(':data_student_id', $student['registration_id']);
							    $stmt->execute();
							    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
							    print $markvalue['data_value'];?>			  			
				  		</td>
				  		<td>										
				  			<?php
				  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																					LEFT JOIN ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																					WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Collaboration'");
							    $stmt->bindValue(':data_student_id', $student['registration_id']);
							    $stmt->execute();
							    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
							    print $markvalue['data_value'];?>			  			
				  		</td>
							
							<td>										
				  			<?php
				  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																					left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																					WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Initiative'");
							    $stmt->bindValue(':data_student_id', $student['registration_id']);
							    $stmt->execute();
							    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
							    print $markvalue['data_value'];?>			  			
				  		</td>
							<td>										
				  			<?php
				  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
																					left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
																					WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Self-Regulation'");
							    $stmt->bindValue(':data_student_id', $student['registration_id']);
							    $stmt->execute();
							    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
							    print $markvalue['data_value'];?>			  			
				  		</td>
						</tr>	
				 	</table>
     
    		<?php 
    		}
    	$studentcount++;
    }
    if ($student['accepted_session'] == 23 or $student['accepted_session']  == 15 AND $student['admin_coop'] == 1)
		{
			$stmt = $conn->prepare("SELECT * FROM `ss_assess_credit`
														WHERE credit_program_id = :credit_program_id AND credit_coop = 'Y'");
		   $stmt->bindValue(':credit_program_id', $student['program_id']);
		   $stmt->execute();
		   $credit = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			  <br />
			  <br />
			  <table style="border: none;">
			  	<tr>
			  		<td style="border: none;" class="left"><span style="font-weight: bold">Credit: </span>
						<?php
							if ($student['admin_credit'] == "")
			  					{
			  						if ($session == 15) {print "PLF4MA";}
									else if ($session == 23) {print "GPP3O5";}
									else print $student['program_credit'];} 
			  				else {print $student['admin_credit'];}
			  			?>
		  			</td>
			  		<td style="border: none;" class="left"><span style="font-weight: bold">Credit Value: </span><?php print $student['program_credit_value'];?></td>
			  	</tr>	
            </table>
            <br />
            <table>
			  	<tr>
			  		<th width="14%">Mark</th>
			  		<th width="14%">Responsibility</th>
			  		<th width="14%">Organization</th>
			  		<th width="14%">Independent Work</th>
			  		<th width="14%">Collaboration</th>			  		
			  		<th width="14%">Initiative</th>
			  		<th width="14%">Self-Regulation</th>			  		
			  	</tr>  	
			  	<tr>
			  		<td>
							<?php 
							$finalmark = 0;
							$totalmark = 0;
							$reweight = 0;
								$stmt = $conn->prepare("SELECT * FROM `ss_assess`
																	WHERE assess_program_id = :assess_program_id AND assess_coop = 'Y'
																	ORDER BY assess_sort");
			    $stmt->bindValue(':assess_program_id', $student['program_id']);
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
							
							
						$count ++;
					}
					$finalmark = ($totalmark / (100 - $reweight)) * 100;
					print ceil($finalmark);
				?>
						</td>
						<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													LEFT JOIN ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Responsibility'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
			  		<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Organization'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
			  		<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Independent Work'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
			  		<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													LEFT JOIN ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Collaboration'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
						
						<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Initiative'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
						<td>										
			  			<?php
			  				$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													left join ss_assess ON ss_assess_data.data_assess_id = ss_assess.assess_id
													WHERE data_student_id = :data_student_id AND assess_coop = 'Y' AND assess_title = 'Self-Regulation'");
						    $stmt->bindValue(':data_student_id', $student['registration_id']);
						    $stmt->execute();
						    $markvalue = $stmt->fetch(PDO::FETCH_ASSOC);
						    print $markvalue['data_value'];?>			  			
			  		</td>
					</tr>	
			 	</table>
     
    	<?php 
    	$studentcount++;
    }?>
	<br />
    <br />
    <table style="border: none;">
    	<tr>
        	<td style="vertical-align: bottom;text-align: left;border: none;">
            	<br />
	            <br />
            	<p>Krishna Burra<br />
                Limestone District School Board</p>
            </td>
            <td style="vertical-align: bottom;text-align: right;border: none;">
                <img src="../images/ldsb.jpg" width="67" height="100" alt="ldsb.jpg">
                <img src="../../shared/GL_sunman.png" width="100" height="97" alt="ldsb.jpg">
            </td>
       </tr>
   </table>
        
	<?php
	$studentcount++;
	if ($studentcount == 2){$studentcount = 0;}
	else {print "<br /><br /><br /><br /><br /><br /><br />";}
	} 
	 ?>	
</body>
</html>
