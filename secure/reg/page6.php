<?php 	
	$page = 6;
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
		<div id="gl-wrapper">	
	    	<div class="pure-g">	      	
				<div class="pure-u-1">	
					<p>Please select the program <?php print $data['student_name_common']; ?> would like to register for.
						Not seeing the course you want? Make sure your age, grade and previous courses are correct.</p>
					<p><em>You will select the specific session on the next page.</em></p>
				</div>
				<?php
				//Base Query
				
				$programquery = "select * from ss_programs where program_id > 0";
				
				//Sort for sex
				if ($data['student_sex'] == "Male")
				{$programquery = $programquery. " and program_code!='GAP' ";}
									
				//Sort for previous programs
				if ($data['completed_oe'] == "Yes")
				{$programquery = $programquery. " and program_code!='OE' ";}
					
				if ($data['completed_gap'] == "Yes")
				{$programquery = $programquery. " and program_code!='GAP' and program_code!='Q' ";}
				
				if ($data['completed_q'] == "Yes")
				{$programquery = $programquery. " and program_code!='Q' and program_code!='GAP' ";}
				
				if ($data['completed_outreach'] == "Yes")
				{$programquery = $programquery. " and program_code!='OR' ";}
				
				if ($data['completed_op'] == "Yes")
				{$programquery = $programquery. " and program_code!='OP'";}
				
				if ($data['completed_os'] == "Yes")
				{$programquery = $programquery. " and program_code!='OS'";}
				
				if ($data['completed_wic'] == "Yes")
				{$programquery = $programquery. " and program_code!='WIC'";}
				
				if ($data['completed_lt'] == "Yes")
				{$programquery = $programquery. " and program_code!='LT'";}
				
				if ($data['completed_kic'] == "Yes")
				{$programquery = $programquery. " and program_code!='KIC'";}
				
				//Programs not to ever include
				$programquery = $programquery. " and program_code !='ST' and program_code !='OLP' order by program_sortorder";
				
				$programcount = 0;
				
				foreach ($conn->query($programquery) as $programs)
				{
					if ($data['student_grade'] >= $programs['program_mingrade'] and $data['student_grade'] <= $programs['program_maxgrade'])
					{ 
						$programcount++;?>
						<div class="pure-u-1">
							<table class="program_select">
								<tr><th colspan=2 width="100%"><p><?php print $programs['program_name']; ?></p></th></tr>
								<tr><td colspan=2>
									<div class="pure-g">
										<div class="pure-u-1 pure-u-sm-2-3">
											<p><?php print $programs['program_description']; ?>
											<p style="font-weight: 400;">Program Cost: $<?php print $programs['program_cost']; ?></p>
										</div>
										<div style="text-align: center;" class="pure-u-1 pure-u-sm-1-3">
											<button id="program_button_<?php print $programs['program_id'] ?>" class="register_button">Register</button>
											<script>
												$(document).ready(function(){
												 	$("#program_button_<?php print $programs['program_id'] ?>").click(function(){
												 		$("#working").show();
											   	 	$.ajax({
											        type: "POST",
											        url: "process-6-course-select.php",
											        data: {selected_program: <?php print $programs['program_id'] ?>},
											        success: function() {
																window.location.href = "page7.php";
										          }
												 		});
												 	});	 
												});
											</script>
										</div>
									</div>
								</td></tr>
								<tr class="header_cells">
									<td width="25%">Session</td>
									<td width="75%">Dates</td>
								</tr>
								<?php
									$sessionquery = "select * from ss_sessions where session_program_code='".$programs['program_code']."' and session_visible='1' order by session_number";
									foreach ($conn->query($sessionquery) as $sessions)
									{?>
										<tr>
											<td>
												<?php print $sessions['session_program_code']." ".$sessions['session_number']; ?>
											</td>
											<td>
												<?php print date("M. d",strtotime($sessions['session_start']))." - ".date("M. d, Y",strtotime($sessions['session_end']));
												if ($sessions['session_comment']!="")
													{print "<br>".$sessions['session_comment'];} ?>
											</td>
										</tr>
									<?php
									}
								?>
							</table>
							<br />
						</div>
					<?php 
					}
				} 
				if ($programcount == 0){print "<div class=\"pure-u-1\"><p style=\"font-weight: bold;\"><em>Oh no! There aren't any courses available based on the information you gave us. Make sure your age, grade and previous courses are correct on the previous pages!</em></p></div>";}
				?>
			</div>
		</div>
	</div>
		
	<?php include ('footer.php'); 
}
?>

