<?php
session_start();

include ('../shared/dbconnect.php');

$student_id = $_POST['student_id'];

$stmt = $conn->prepare("SELECT registration_id, accepted_session, session_program_code, program_id, admin_coop, admin_coop_yes, admin_credit, admin_absent, student_name_common FROM `ss_registrations`  
						left join ss_sessions on ss_registrations.accepted_session = ss_sessions.session_id
						left join ss_programs on ss_sessions.session_program_code = ss_programs.program_code
						where registration_id = :registration_id");
$stmt->bindValue(':registration_id', $student_id);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if ($student['accepted_session'] == 0) 
	{print "<div style=\"text-align: center;\">Student is not currently accepted into a session!</div>";}
elseif ($student['program_id'] ==  10 OR $student['program_id'] ==  12 OR $student['program_id'] ==  14)
	{print "<div style=\"text-align: center;\">The program ".$student['student_name_common']." is enrolled in does not require assessments.</div>";}
elseif ($student['admin_coop_yes'] ==  1)
	{print "<div style=\"text-align: center;\">Coop student assessments should be completed in the WIC or KIC registration file.</div>";}
else {
	$count = 0;	
	$totalmark = 0;	
	$reweight = 0;
	
	$stmt = $conn->prepare("SELECT * FROM `ss_assess`
							WHERE assess_program_id = :assess_program_id AND assess_coop = 'N'
							ORDER BY assess_sort");
	$stmt->bindValue(':assess_program_id', $student['program_id']);
	$stmt->execute();
	$assessments = $stmt->fetchAll();
	?>
	
	<form class="assess-form">
		<input type="hidden" name="student_id" value="<?php print $student_id; ?>">
		<div class="pure-g">	
			<div class="pure-u-1 pure-u-md-5-8">
				<h2>Course Achievement</h2>
				<table>
					<tr>
						<th style="text-align: left;">Assessment</th>
						<th>Weight</th>
						<th>Type</th>
						<th>Grade</th>
						<th>Mark</th>
					</tr>
									
					<?php					
					foreach ($assessments as $assessment)
					{?>
						<tr>
							<td style="text-align: left;"><?php print $assessment['assess_title']; ?></td>
							<td><?php print $assessment['assess_weight']."%"; ?></td>
							<td>
								<?php
								if ($assessment['assess_type'] == "T"){print "Test (".$assessment['assess_test_mark'].")";}
								else {print "Level";}
								?>
							</td>
							<td>
								<?php
								$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
														WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
							   $stmt->bindValue(':data_assess_id', $assessment['assess_id']);
							  	$stmt->bindValue(':data_student_id', $student_id);
							   $stmt->execute();
							   $mark = $stmt->fetch(PDO::FETCH_ASSOC);
							    ?>    	
								   	<input type="hidden" name="data_id[<?php print $count; ?>]" value="<?php print $mark['data_id']; ?>">
							    	<input type="hidden" name="data_assess_id[<?php print $count; ?>]" value="<?php print $assessment['assess_id']; ?>">
								    <?php
								    if ($assessment['assess_type'] == "L") //LEVEL SELECT
									{?>
										<select class="pure-input-1 assess-input" name="data_value[<?php print $count; ?>]">
							    			<option value="">-</option>
								    		<?php	
								    		$valuequery = "SELECT * FROM ss_assess_value ORDER BY value_id DESC";
											$assessoptions = $conn->query($valuequery);
									    	foreach ($assessoptions as $value)
									    	{
												print "<option value=\"".$value['value_level']."\"";
												if ($value['value_level'] == $mark['data_value']){print " selected";}
												print ">".$value['value_level']."</option>"; 
											}?>
										</select>
									<?php
									}
									else // OR MARK
									{?>
										<input type="text" class="pure-input-1 assess-input" name="data_value[<?php print $count; ?>]" value="<?php print $mark['data_value']; ?>">
									<?php
									}?>															
							</td>
							<td>
								<?php 
								if ($assessment['assess_type'] == "L")
								{
									$stmt = $conn->prepare("SELECT * FROM `ss_assess_value`
															WHERE value_level = :value_level");
							    	$stmt->bindValue(':value_level', $mark['data_value']);
							    	$stmt->execute();
							    	$value = $stmt->fetch(PDO::FETCH_ASSOC);	
										
									$sectionmark = $value['value_percent']*$assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									print round($sectionmark,2)."%";
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
								}
								else 
								{
									$sectionmark = $mark['data_value'] / $assessment['assess_test_mark'] * $assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									print round($sectionmark,2)."%";
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
								}
								
								?>
							</td>
						</tr>
						<?php
						$count ++;
					}
					if ($reweight == 100) {$finalmark = 0;}
					else {$finalmark = ($totalmark / (100 - $reweight)) * 100;}
					?>
					<tr style="background: #BBB;">
						<td colspan=4 style="text-align: left;background-color: #F7F7F7;">Total</td>
						<td  style="background-color: #F7F7F7;"><?php print ceil($finalmark); ?>%</td>
					</tr>
				</table>				
			</div>
		
			<div class="pure-u-1 pure-u-md-3-8">
				<h2>Learning Skills</h2>
				<table>
					<tr>
						<th style="text-align: left;">Learning Skill</th>
						<th>Mark</th>
					</tr>
						<?php
						//Learning Skills
						$stmt = $conn->prepare("SELECT * FROM `ss_assess`
												WHERE assess_sum = 'LS' AND assess_coop = 'N'
												ORDER BY assess_sort");
					   $stmt->execute();
						$assessments = $stmt->fetchAll();
							
						foreach ($assessments as $assessment)
					    { ?>
							<td style="text-align: left;"><?php print $assessment['assess_title']; ?></td>
							
							<?php 
							$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
						   $stmt->bindValue(':data_assess_id', $assessment['assess_id']);
						   $stmt->bindValue(':data_student_id', $student_id);
						   $stmt->execute();
						   $mark = $stmt->fetch(PDO::FETCH_ASSOC);?>
						   <td>	
							       	<input type="hidden" name="data_id[<?php print $count; ?>]" value="<?php print $mark['data_id']; ?>">
							    	<input type="hidden" name="data_assess_id[<?php print $count; ?>]" value="<?php print $assessment['assess_id']; ?>">		    	
					    			<select class="pure-input-1 assess-input" name="data_value[<?php print $count; ?>]">
				    					<option value="">-</option>";
					    				<?php
										$assessoptions = array("E","G","S","NI");
							    		foreach ($assessoptions as $assessvalue)
							    		{?>
											<option value="<?php print $assessvalue; ?>"
											<?php
											if ($assessvalue == $mark['data_value'])
												{print " selected";}
											?>
											><?php print $assessvalue; ?></option>
										<?php
										}?>
									</select>		
																		
							</td>
						</tr>
						<?php
						$count ++;
					}
					?>		
				</table>
			</div>
			<button type="submit" class="plaintext-button save-button" style="font-size: 1.3em;padding-top: 3px;"><i class="fa fa-floppy-o"></i> save</button>
		</div>
	</form>	
	<?php
	if ($student['program_id'] == 6 OR $student['program_id'] == 7)
	{
		$count = 0;	
		$totalmark = 0;	
		$reweight = 0;
		
		$stmt = $conn->prepare("SELECT * FROM `ss_assess`
								WHERE assess_program_id = :assess_program_id AND assess_coop = 'Y'
								ORDER BY assess_sort");
		$stmt->bindValue(':assess_program_id', $student['program_id']);
		$stmt->execute();
		$assessments = $stmt->fetchAll();
		?>
		<hr />
		<form class="assess-form">
	    	<input type="hidden" name="student_id" value="<?php print $student_id; ?>">
			<div class="pure-g">
				<div class="pure-u-1 pure-u-md-5-8">
					<h2>Course 2 Achievement</h2>
					<table>
						<tr>
							<th style="text-align: left;">Assessment</th>
							<th>Weight</th>
							<th>Type</th>
							<th>Grade</th>
							<th>Mark</th>
						</tr>
										
						<?php					
						foreach ($assessments as $assessment)
						{?>
							<tr>
								<td style="text-align: left;"><?php print $assessment['assess_title']; ?></td>
								<td><?php print $assessment['assess_weight']."%"; ?></td>
								<td>
									<?php
									if ($assessment['assess_type'] == "T"){print "Test (".$assessment['assess_test_mark'].")";}
									else {print "Level";}
									?>
								</td>
								<td>
									<?php
									$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
															WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
								   $stmt->bindValue(':data_assess_id', $assessment['assess_id']);
								  	$stmt->bindValue(':data_student_id', $student_id);
								   $stmt->execute();
								   $mark = $stmt->fetch(PDO::FETCH_ASSOC);
								    ?>
							    
								   	<input type="hidden" name="data_id[<?php print $count; ?>]" value="<?php print $mark['data_id']; ?>">
							    	<input type="hidden" name="data_assess_id[<?php print $count; ?>]" value="<?php print $assessment['assess_id']; ?>">
								    <?php
								    if ($assessment['assess_type'] == "L") //LEVEL SELECT
									{?>
										<select class="pure-input-1 assess-input" name="data_value[<?php print $count; ?>]">
							    			<option value="">-</option>
								    		<?php	
								    		$valuequery = "SELECT * FROM ss_assess_value ORDER BY value_id DESC";
											$assessoptions = $conn->query($valuequery);
									    	foreach ($assessoptions as $value)
									    	{
												print "<option value=\"".$value['value_level']."\"";
												if ($value['value_level'] == $mark['data_value']){print " selected";}
												print ">".$value['value_level']."</option>"; 
											}?>
										</select>
									<?php
									}
									else // OR MARK
									{?>
										<input type="text" class="pure-input-1 assess-input" name="data_value[<?php print $count; ?>]" value="<?php print $mark['data_value']; ?>">
									<?php
									}?>			
								</form>														
							</td>
							<td>
								<?php 
								if ($assessment['assess_type'] == "L")
								{
									$stmt = $conn->prepare("SELECT * FROM `ss_assess_value`
															WHERE value_level = :value_level");
							    	$stmt->bindValue(':value_level', $mark['data_value']);
							    	$stmt->execute();
							    	$value = $stmt->fetch(PDO::FETCH_ASSOC);	
										
									$sectionmark = $value['value_percent']*$assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									print round($sectionmark,2)."%";
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
								}
								else 
								{
									$sectionmark = $mark['data_value'] / $assessment['assess_test_mark'] * $assessment['assess_weight'];
									$totalmark = $totalmark + $sectionmark;
									print round($sectionmark,2)."%";
									if ($mark['data_value'] == "")
									{
										$reweight = $reweight + $assessment['assess_weight'];
									}
								}
								
								?>
							</td>
						</tr>
						<?php
						$count ++;
					}
					if ($reweight == 100) {$finalmark = 0;}
					else {$finalmark = ($totalmark / (100 - $reweight)) * 100;}
					?>
					<tr style="background: #BBB;">
						<td colspan=4 style="text-align: left;background-color: #F7F7F7;">Total</td>
						<td  style="background-color: #F7F7F7;"><?php print ceil($finalmark); ?>%</td>
					</tr>
				</table>				
			</div>
		
			<div class="pure-u-1 pure-u-md-3-8">
				<h2>Course 2 Learning Skills</h2>
				<table>
					<tr>
						<th style="text-align: left;">Learning Skill</th>
						<th>Mark</th>
					</tr>
						<?php
						//Learning Skills
						$stmt = $conn->prepare("SELECT * FROM `ss_assess`
												WHERE assess_sum = 'LS' AND assess_coop = 'Y'
												ORDER BY assess_sort");
					   $stmt->execute();
						$assessments = $stmt->fetchAll();
							
						foreach ($assessments as $assessment)
					    { ?>
							<td style="text-align: left;"><?php print $assessment['assess_title']; ?></td>
							
							<?php 
							$stmt = $conn->prepare("SELECT * FROM `ss_assess_data`
													WHERE data_assess_id = :data_assess_id AND data_student_id = :data_student_id");
						   $stmt->bindValue(':data_assess_id', $assessment['assess_id']);
						   $stmt->bindValue(':data_student_id', $student_id);
						   $stmt->execute();
						   $mark = $stmt->fetch(PDO::FETCH_ASSOC);?>
						   <td>	
							       	<input type="hidden" name="data_id[<?php print $count; ?>]" value="<?php print $mark['data_id']; ?>">
							    	<input type="hidden" name="data_assess_id[<?php print $count; ?>]" value="<?php print $assessment['assess_id']; ?>">		    	
					    			<select class="pure-input-1 assess-input" name="data_value[<?php print $count; ?>]">
				    					<option value="">-</option>";
					    				<?php
										$assessoptions = array("E","G","S","NI");
							    		foreach ($assessoptions as $assessvalue)
							    		{?>
											<option value="<?php print $assessvalue; ?>"
											<?php
											if ($assessvalue == $mark['data_value'])
												{print " selected";}
											?>
											><?php print $assessvalue; ?></option>
										<?php
										}?>
									</select>									
							</td>
						</tr>
						<?php
						$count ++;
					}
					?>		
				</table>
			</div>
			<button type="submit" class="plaintext-button save-button" style="font-size: 1.3em;padding-top: 3px;"><i class="fa fa-floppy-o"></i> save</button>
		</div>
	</form>
	<?php
	}
}	
?>



<script>
	$(document).ready(function() {
		
		$(".assess-form").submit(function(e){
	   		e.preventDefault();
	 	
	 		$('#working').fadeIn('fast');
	   		
	   		var senddata = $(this).serialize(); 	 	
	   		senddata = senddata.replace(/%5B/g, '[');
		  	senddata = senddata.replace(/%5D/g, ']');
 	 		//alert (senddata);return false;
 	 	
	    	$.ajax({
	      		type: "POST",
	      		url: "process-student-assess.php",
	      		data: senddata,
	      		success: function() {
	      			$("#assessment-table").load("student-assess-table.php",{student_id : <?php print $student_id; ?>},function() {
			  			$("#assessment-table").fadeIn("fast",function() {
			  				$('#working').fadeOut('fast');
			  			});
			  		});
	      		}           
    		}); 
	  	});			
	});
</script> 