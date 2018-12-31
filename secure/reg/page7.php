<?php 	
	$page = 7;
	include ('header.php');
		
	if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from ss_registrations where registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	}

if ($_SESSION['registration_id'] == 0){header("Location: http://outed.limestone.on.ca/regfiles/");}	 
else {
?>
	<div id="gl-main">
		<?php include("menu.php"); ?>
		<div id="gl-wrapper" style="min-height: 100%">	    
	  		<form class="pure-form pure-form-stacked" method="post" parsley-validate parsley-required-message="">
	  			<div class="pure-g">	      	
					<div class="pure-u-1">					
	  				<p>Please select the session <?php print $data['student_name_common']; ?>  would like to register for.</p>
					<?php
							
						$stmt = $conn->prepare("SELECT * FROM ss_programs where program_id = :program_id");
						$stmt->bindValue(':program_id', $data['selected_program']);
				    	$stmt->execute();
						$programs = $stmt->fetch(PDO::FETCH_ASSOC);
			    
			    		//FIRST CHOICE LIST
			    	?>
					<div class="pure-u-1">
						<table class="program_select">
							<tr><th colspan=3 width="100%"><p><?php print $programs['program_name']." - First Choice"; ?></p></th></tr>
							<tr class="header_cells">
								<td width="20%">Select</td>
								<td width="20%">Session</td>
								<td width="60%">Dates</td>
							</tr>
							<?php
								$sessionquery = "select * from ss_sessions where session_program_code='".$programs['program_code']."' and session_visible='1' order by session_number";
								foreach ($conn->query($sessionquery) as $sessions)
								{ ?>
									<tr>
										<td align=center>
											<input class="session_1_radio" style="width: 50px; height: 20px;" type=radio name=selected_session1
											<?php 
												if ($data['selected_session1'] == $sessions['session_id']) 
												{print " checked ";}
											?>	
											value="<?php print $sessions['session_id']; ?>" required/>
										</td>
										<td><?php print $sessions['session_program_code']." ".$sessions['session_number']; ?></td>
										<td>
											<?php 
												print date("M. d",strtotime($sessions['session_start']))." - ".date("M. d, Y",strtotime($sessions['session_end']));							
												if ($sessions['session_comment'] != "")
												{print "<br>".$sessions['session_comment'];}
											?>
										</td>
									</tr>
								<?php 
								}
								?>
							</table>
							<br />
						</div>
					</div>
					<?php 
					//Choice 2
					if ($programs['program_code'] != "WIC" and $programs['program_code'] != "KIC" and $programs['program_code'] != "SOLO" and $programs['program_code'] != "LT")
					{ ?>
						<div class="pure-u-1">		
							<?php		
								if ($data['selected_program']==1)
								{
									print "<p>GAP applicants can select a QUEST session as a second and third choice in the event that
									the GAP session is full.</p><br />";
									$stmt = $conn->prepare("SELECT * FROM ss_programs where program_id = '2'");
						    	$stmt->execute();
									$programs = $stmt->fetch(PDO::FETCH_ASSOC);
								}
							?>
							<table class="program_select">
								<tr><th colspan=3><p><?php print $programs['program_name']." - Second Choice"; ?></p></th></tr>
								<tr class="header_cells">
									<td width="20%">Select</td>
									<td width="20%">Session</td>
									<td width="60%">Dates</td>
								</tr>
								<?php
									$sessionquery = "select * from ss_sessions where session_program_code='".$programs['program_code']."' and session_visible='1' order by session_number";
									foreach ($conn->query($sessionquery) as $sessions)
									{ ?>
										<tr  class="session_2_radio" id="<?php print $sessions['session_id']; ?>">
											<td align=center>
												<input style="width: 50px; height: 20px;" type=radio name=selected_session2
												<?php 
													if ($data['selected_session2'] == $sessions['session_id']) 
													{print " checked ";}
												?>	
												value="<?php print $sessions['session_id']; ?>"/>
											</td>
											<td><?php print $sessions['session_program_code']." ".$sessions['session_number']; ?></td>
											<td>
												<?php 
													print date("M. d",strtotime($sessions['session_start']))." - ".date("M. d, Y",strtotime($sessions['session_end']));							
													if ($sessions['session_comment'] != "")
													{print "<br>".$sessions['session_comment'];}
												?>
											</td>
										</tr>
									<?php 
									}
								?>
							</table>
							<br />
						</div>
					</div>
					<?php 
					}
					//PLACEMENT INFORMATION
					if ($programs['program_code'] == "WIC" OR $programs['program_code'] == "KIC")
					{?>
						<div class="pure-u-1">	
							<p>The second credit of the WIC course is a 16 day co-op placement in one of our Junior Programs. Please provide us with any information regarding unavailable dates which would help us in arranging a placement for you. 
							
		          			<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="selected_placement" id="student_custody_details"><?php if (isset($data['selected_placement'])) {print $data['selected_placement'];} ?></textarea>
							<br />
						</div>
					<?php
					}
					
					//ALTERNATIVE COURSE SELECTION
					if ($data['selected_program']>=4 and $data['selected_program']<=7)
					{?>
						<div class="pure-u-1">
							<table class="program_select">
								<tr><th colspan=2><p>Alternate Course Selection</p></th></tr>
								<tr>
									<td style="width: 75%">
									<?php
										if ($data['selected_program']==4)
											{print "If the Outdoor Pursuits program is not available due to enrolment constraints, would you accept a place in Outdoor Skills?";}
										if ($data['selected_program']==5)
											{print "If the Outdoor Skills program is not available due to enrolment constraints, would you accept a place in Outdoor Pursuits?";}
										if ($data['selected_program']==6)
											{print "If the Wilderness Instructor Course program is not available due to enrolment constraints, would you accept a place in Kayak Instructor Course and/or Long Trail?";}

										if ($data['selected_program']==7)
											{print "If the Kayak Instructor Course program is not available due to enrolment constraints, would you accept a place in Wilderness Instructor Course or Long Trail?";}


									?>
									</td>
									<td style="width: 25%">
										<input style="width: 50px; height: 20px;" type=checkbox name=selected_alternate value="Yes"
										<?php
											if ($data['selected_alternate'] == "Yes") {print " checked";}
										?>
										>
									</td>
								</tr>
							</table>
						</div>
					<?php
					}
					?>
					<div class="pure-u-1 button-home">
		     		<button class="gl-submit-button">Save and continue to confirmation <i class="fa fa-hand-o-right"></i></button>
		      </div>
				</div>
			</form>
		</div>
	</div>
	
	<?php include ('footer.php'); 
}
?>

<script>

	$(document).ready(function(){
		
		$(".session_1_radio").change(function(){
			var radio_value = $(this).val();
			var myId = '#' + radio_value;
			$(".session_2_radio").show();
			$(myId).hide();
			
	 });
		
	 	$(".gl-submit-button").click(function(){
			$(".pure-form").submit(function(e){
		  	e.preventDefault();
				if ( $(this).parsley().isValid() ) {
					$("#working").show();
					var senddata = $(this).serialize();
		   	 	//alert (senddata);return false;
		   	 	
		   	 	$.ajax({
		        type: "POST",
		        url: "process-7-session-select.php",
		        data: senddata,
		        success: function() {
							window.location.href = "page8.php";
	          }
	     	 	});	     	 	
	     	}
	 		});
	 	});	 
	});
	
</script>