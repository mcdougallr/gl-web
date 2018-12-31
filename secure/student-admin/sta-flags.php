<?php 
	$page_title = "GL Student Flags";
	include ("sta-header.php"); 

	if (isset($_GET['sid'])) {$session_id = cleantext($_GET['sid']);}
	else {$session_id = 0;}

	if ($session_id != 0) 
	{
		$sessionquery = "SELECT * FROM ss_sessions WHERE session_id = ".$session_id." ORDER BY session_sortorder";
		$stmt = $conn->prepare("SELECT session_program_code, session_number FROM ss_sessions
													WHERE session_id = :session_id");
		$stmt->bindValue(':session_id', $session_id);
		$stmt->execute();
		$session = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	else {$sessionquery = "SELECT * FROM ss_sessions ORDER BY session_sortorder";}
?> 

<link rel="stylesheet" href="_sta_flags.css">

<h1>Flags - <?php 
	if ($session_id == 0) {print "All";}
	else {print $session['session_program_code']. $session['session_number'];}
?>
</h1>
<div id="session-table">
	<table>
		<tr>
			<th style="width: 25%;">Student</th>
			<th style="width: 15%;"<?php if ($session_id != 0) {print " class=\"show-for-all\"";}?>>Session</th>
			<th style="width: 15%;" align=center>Medical</th>
			<th style="width: 15%;" align=center>Dietary</th>
			<th style="width: 15%;" align=center>IEP</th>
			<th style="width: 15%;" align=center>Social Media</th>
		</tr>
		<?php
		$bg = "#FFF";
		$session_new = "";
		$session_old = "";
		
		foreach ($conn->query($sessionquery) as $sessions)
		{
			$stmt = $conn->prepare("SELECT * FROM ss_registrations
															LEFT JOIN ss_sessions ON ss_registrations.accepted_session=ss_sessions.session_id 
															WHERE admin_df = '1' AND accepted_session = :accepted_session 
															OR admin_mf = '1' AND accepted_session = :accepted_session 
															OR admin_if = '1' AND accepted_session = :accepted_session 
															OR admin_sf = '1' AND accepted_session = :accepted_session 
															OR admin_flag_checked != '1' AND accepted_session = :accepted_session
															ORDER BY student_name_last, student_name_first");
			$stmt->bindValue(':accepted_session', $sessions['session_id']);
			$stmt->execute();
			$result = $stmt->fetchAll();

			foreach ($result as $student)
	    	{
	    		$session_new = $sessions['session_id'];	
	    		if ($session_new != $session_old) {
	    			if ($bg == "#EEE"){$bg = "#FFF";}
					else {$bg = "#EEE";}
				}
				?>
				<tr style="background-color: <?php print $bg; ?>;">
					<td style="text-align: left;">
						<a href="../student-edit/index.php?sid=<?php print $student['registration_id']; ?>#medical" class="invisible-button" target="_blank"><?php print $student['student_name_last'] . ", ". $student['student_name_first']; ?></a>
						<?php
						if ($student['completed_oe'] == "Yes" OR $student['completed_gap'] == "Yes" OR $student['completed_q'] == "Yes" OR $student['completed_outreach'] == "Yes" OR $student['completed_op'] == "Yes" 
							OR $student['completed_os'] == "Yes" OR $student['completed_wic'] == "Yes" OR$student['completed_kic'] == "Yes")
							{print "<i style=\"margin-left: 5px;\" class=\"fa fa-refresh\"></i>";}
						?>
					</td>	
					<td style="text-align: center;"<?php if ($session_id != 0) {print " class=\"show-for-all\"";}?>>
						<?php print $sessions['session_program_code']." ". $sessions['session_number']; ?>
					</td>
					<?php
					if ($student['admin_flag_checked'] == 1)
					{
						if ($student['admin_mf'] == 1)
						{?>
								<td class="small_td" <?php
									if ($student['admin_mfc']) {print "style=\"background-color: transparent;\"";}
									else {print "style=\"background-color: #C03030;\"";} ?>
								><i class="fa fa-flag"></i>
								<?php 
								if ($student['admin_mfc'] == 1) {print " Closed";}
								elseif ($student['admin_mf_contact'] == 1) {print " Contacted";}
								else {print " Flagged";}
								print "</td>";
						}
						else 
						{?>
							<td class="small_td"></td>
						<?php
						}
						if ($student['admin_df'] == 1)
						{?>
								<td class="small_td" <?php
									if ($student['admin_dfc']) {print "style=\"background-color: transparent;\"";}
									else {print "style=\"background-color: #F0F090;\"";} ?>
								><i class="fa fa-flag"></i>
								<?php 
								if ($student['admin_dfc'] == 1) {print " Closed";}
								elseif ($student['admin_df_contact'] == 1) {print " Contacted";}
								else {print " Flagged";}
								print "</td>";
						}
						else 
						{?>
							<td class="small_td"></td>
						<?php
						}
						if ($student['admin_if'] == 1)
						{?>
								<td class="small_td" <?php
									if ($student['admin_ifc']) {print "style=\"background-color: transparent;\"";}
									else {print "style=\"background-color: #6CBDFF;\"";} ?>
								><i class="fa fa-flag"></i>
								<?php 
								if ($student['admin_ifc'] == 1) {print " Closed";}
								elseif ($student['admin_if_contact'] == 1) {print " Contacted";}
								else {print " Flagged";}
								print "</td>";
						}
						else 
						{?>
							<td class="small_td"></td>
						<?php
						}
						if ($student['admin_sf'] == 1)
						{?>
								<td class="small_td" <?php
									if ($student['admin_sfc']) {print "style=\"background-color: transparent;\"";}
									else {print "style=\"background-color: #666;\"";} ?>
								><i class="fa fa-flag"></i>
								<?php 
								if ($student['admin_sfc'] == 1) {print " Closed";}
								elseif ($student['admin_sf_contact'] == 1) {print " Contacted";}
								else {print " Flagged";}
								print "</td>";
						}
						else 
						{?>
							<td class="small_td"></td>
						<?php
						}
					}
					else
					{?>
						<td class="small_td" colspan=4>File has not been checked.</td>
					<?php
					}
					?>
				</tr>
			<?php			
			$session_old = $session_new;		
			}
		}
		?>
	</table>
</div>


<?php include("sta-footer.php"); ?>