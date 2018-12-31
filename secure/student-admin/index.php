<?php 
	$page_title = "GL Student Admin";
	include ("sta-header.php"); 
?>

<link rel="stylesheet" href="_index.css">

<div id="registration_overview" class="pure-g">
	<div id="session-table" class="pure-u-1 pure-u-md-5-8">
		<h1>Registration Overview</h1>
		<table class="pure-table pure-table-bordered">
			<tr>
				<th  style="text-align: left">Session</th>
		        <th>Capacity</th>
		        <th>Registered</th>
		        <th>Available</th>
    		</tr>

			<?php    
			$bg = "#F7F7F7";
			$programquery = "SELECT * FROM ss_programs ORDER BY program_sortorder";
			foreach ($conn->query($programquery) as $programs)
			{
				$stmt = $conn->prepare("SELECT * FROM ss_sessions 
															WHERE session_program_code = :session_program_code
															ORDER BY session_number");
		    	$stmt->bindValue(':session_program_code', $programs['program_code']);
		    	$stmt->execute();
				$result = $stmt->fetchAll();
				
				if ($bg == "#F2F2F2"){$bg = "#FFF";}
				else {$bg = "#F2F2F2";}
				
				foreach ($result as $sessions)
	    		{
					$placements = 0; ?>
					<tr style="background-color: <?php print $bg; ?>;">
						<td style="text-align: left">
							<a  href="sta-session-list.php?sid=<?php print $sessions['session_id']; ?>" class="plaintext-button"><?php print $sessions['session_program_code']. " " . $sessions['session_number']; ?></a>
						</td>
						<td align=center><?php print $sessions['session_capacity']; ?></td>
						<?php
						//Calculate Spots Remaining
						$stmt = $conn->prepare("SELECT registration_id FROM ss_registrations 
																		WHERE accepted_session = :accepted_session");
						$stmt->bindValue(':accepted_session', $sessions['session_id']);
				    	$stmt->execute();
						$countresult = $stmt->fetchAll();
						$registered = count($countresult);
						?>
						<td align=center><?php print $registered; ?></td>

						<?php $available = $sessions['session_capacity'] - $registered; ?>
				
						<td align=center><?php print $available; ?></td>
					</tr>
					<?php
				}
			}
		?>
		</table>
	</div>
	<div id="report-section" class="pure-u-1 pure-u-md-3-8">
		<h1>Print Med Form</h1>
		<form id="med-form-print" class="pure-form pure-form-stacked" method="POST" action="sta-reports-med-pick.php">
			<?php
			$sessionsquery = "SELECT * FROM ss_sessions 
								LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
								WHERE program_id > 0 AND session_program_code != 'ST'
								GROUP BY ss_sessions.session_id
								ORDER BY ss_programs.program_sortorder, ss_sessions.session_number";
			?>
			<select id="med-form-session-id" name="sid">
				<option value="">Select a Session</option>
				<option value="all">All Students</option>
				<?php
					foreach ($conn->query($sessionsquery) as $sessions)
					{ ?>
						<option value="<?php print $sessions['session_id']; ?>">
							<?php print $sessions['session_program_code'].$sessions['session_number']; ?>
						</option>
				<?php } ?>					
			</select>
			<button type="submit" class="plaintext-button"><i class="fa fa-print"></i> print med forms</button>
		</form>
		<h1>Session Reports</h1>
		<form id="session-report-print-form" class="pure-form pure-form-stacked">
  			<select id="session_report" name="selected_report">        
			    <option value="">Select Report</option>
			    <option value="courselist">Course List (Gender,DOB,OEN)</option>
			    <option value="schoolinfo">School Info (OEN,LDSB?,Schools)</option>
			  	<option value="tshirtorder">T-Shirt Order</option>
			  	<option value="nonldsb">Non-LDSB Students</option>
			  	<option value="pexperience">Previous Experience</option>
			  	<option value="dietflag">Dietary Details</option>
				<option value="dietflagstaff">Dietary Details Staff</option>
			  	<option value="medflag">Medical Details</option>
			  	<option value="iepflag">IEP Details</option>
			  	<option value="attendanceOE">OE Attendance</option>
			  	<option value="attendanceQGAP">Q/GAP Attendance</option>
				<option value="attendanceOR">OR Attendance</option>			  
				<option value="assignOR">OR Assignments</option>			  
				<option value="attendanceOP">OP Attendance</option>	
				<option value="attendanceOS">OS Attendance</option>			  
				<option value="saf">Student Achievement Form</option>			  
				<option value="receipt">Receipt</option>			
				<option value="assess">Assessment Data</option>	
				<option value="assessdetails">Assessment Data Details</option>				  		  
			</select>
			<?php
			$sessionsquery = "SELECT * FROM ss_sessions 
											LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
											WHERE program_id > 0 AND session_program_code != 'ST'
											ORDER BY ss_programs.program_sortorder, ss_sessions.session_number";
			?>	
			<select id="selected_session" name="session_id">
				<option value="">Select a Session</option>
				<?php
					foreach ($conn->query($sessionsquery) as $sessions)
					{?>
						<option value="<?php print $sessions['session_id']; ?>">
							<?php print $sessions['session_program_code'].$sessions['session_number']; ?>
						</option>
					<?php } ?>		
				<option value="staff">Staff</option>
			</select>
			<button type="submit" class="plaintext-button"><i class="fa fa-file"></i> run report</button>
		</form>
		<h1>Program Reports</h1>
		<form id="program-report-print-form" class="pure-form pure-form-stacked">
  			<select id="program_report" name="selected_report">        
			    <option value="">Select Report</option>
			    <option value="schoolinfo">School Info (OEN,LDSB?,Schools)</option>
			  	<option value="tshirtorder">T-Shirt Order</option>
			  	<option value="nonldsb">Non-LDSB Students</option>
			  	<option value="pexperience">Previous Experience</option>
			  	<option value="dietflag">Dietary Details</option>
			  	<option value="medflag">Medical Details</option>
			  	<option value="iepflag">IEP Details</option>
			  	<option value="assess">Assessment Data</option>
				<option value="placement">Placement Info (ALL)</option>	
				<option value="notpaid">Not Paid (ALL)</option>			  
				<option value="nopic">No Pics or Twitter (ALL)</option>	
				<option value="iepdetails">IEP Details (ALL)</option>	
				<option value="creditsummary">Credit Summary (ALL)</option>						  		  
			</select>
			<?php
			$programquery = "SELECT * FROM ss_programs 
											WHERE program_id > 0 AND program_code != 'ST'
											ORDER BY program_sortorder";
			?>	
			<select id="selected_program" name="program_id">
				<option value="">Select a Program</option>
				<?php
					foreach ($conn->query($programquery) as $program)
					{?>
						<option value="<?php print $program['program_id']; ?>">
							<?php print $program['program_name']; ?>
						</option>
					<?php } ?>							
			</select>
			<button type="submit" class="plaintext-button"><i class="fa fa-file"></i> run report</button>
		</form>
	</div>
</div>
	
<?php include("sta-footer.php"); ?>

<script>

$(document).ready(function(){
	
	$("#session-report-print-form").submit(function(e) {				    
		e.preventDefault();
		
		report = $("#session_report").val();
		//alert(report);
		session = $("#selected_session").val();
		//alert(session);
		if (report == "") {alert("Please select a report.");return false;}
		if (session == "") {alert("Please select a session.");return false;}

		//alert(report+program+session);
		if (report == "courselist"){window.open("reports/s-course-list.php?s="+session,'_blank');}
		if (report == "tshirtorder"){window.open("reports/ps-tshirtorder.php?s="+session,'_blank');}
		if (report == "schoolinfo"){window.open("reports/ps-schoolinfo.php?s="+session,'_blank');}
		if (report == "attendanceOR"){window.open("reports/s-attendanceOR.php?s="+session,'_blank');}
		if (report == "assignOR"){window.open("reports/s-assignOR.php?s="+session,'_blank');}
		if (report == "attendanceOE"){window.open("reports/s-attendanceOE.php?s="+session,'_blank');}
		if (report == "attendanceOP"){window.open("reports/s-attendanceOP.php?s="+session,'_blank');}
		if (report == "attendanceOS"){window.open("reports/s-attendanceOS.php?s="+session,'_blank');}
		if (report == "attendanceQGAP"){window.open("reports/s-attendanceQGAP.php?s="+session,'_blank');}
		if (report == "nonldsb"){window.open("reports/ps-nonldsb.php?s="+session,'_blank');}
		if (report == "pexperience"){window.open("reports/ps-pexperience.php?s="+session,'_blank');}
		if (report == "dietflag"){window.open("reports/ps-dietflag.php?s="+session,'_blank');}
			if (report == "dietflagstaff"){window.open("reports/ps-dietflagstaff.php",'_blank');}
		if (report == "medflag"){window.open("reports/ps-medflag.php?s="+session,'_blank');}
		if (report == "iepflag"){window.open("reports/ps-iepflag.php?s="+session,'_blank');}
		if (report == "saf"){window.open("reports/s-saf.php?s="+session,'_blank');}
		if (report == "receipt"){window.open("reports/s-receipt.php?s="+session,'_blank');}
		if (report == "assess"){window.open("reports/ps-assessment.php?s="+session,'_blank');}
		if (report == "assessdetails"){window.open("reports/ps-assessment-detail.php?s="+session,'_blank');}
	});
	
	$("#program-report-print-form").submit(function(e) {				    
		e.preventDefault();
		
		report = $("#program_report").val();
		//alert(report);
		program = $("#selected_program").val();
		//alert(session);
		if (report == ""){alert("Please select a report.");return false;}
		if (report != "notpaid" && report != "nopic" && report != "iepdetails" && report != "creditsummary") {
			if (program == "") {alert("Please select a program.");return false;}
		}

		//alert(report+program+session);
		if (report == "tshirtorder"){window.open("reports/ps-tshirtorder.php?p="+program,'_blank');}
		if (report == "schoolinfo"){window.open("reports/ps-schoolinfo.php?p="+program,'_blank');}
		if (report == "nonldsb"){window.open("reports/ps-nonldsb.php?p="+program,'_blank');}
		if (report == "pexperience"){window.open("reports/ps-pexperience.php?p="+program,'_blank');}
		if (report == "dietflag"){window.open("reports/ps-dietflag.php?p="+program,'_blank');}
		if (report == "medflag"){window.open("reports/ps-medflag.php?p="+program,'_blank');}
		if (report == "iepflag"){window.open("reports/ps-iepflag.php?p="+program,'_blank');}
		if (report == "iepflag"){window.open("reports/a-ieplist.php?p="+program,'_blank');}
		if (report == "assess"){window.open("reports/ps-assessment.php?p="+program,'_blank');}
		if (report == "placement"){window.open("reports/a-placement.php",'_blank');}
		if (report == "notpaid"){window.open("reports/a-notpaid.php",'_blank');}
		if (report == "nopic"){window.open("reports/a-nopics.php",'_blank');}
		if (report == "iepdetails"){window.open("reports/a-ieplist.php",'_blank');}
		if (report == "creditsummary"){window.open("reports/p-credit-summary.php",'_blank');}
	});
		
});

</script>