<?php 
	$page_title = "GL Assign Students";
	include ("sta-header.php"); 

	$program_id = cleantext($_GET['pid']);
	
	$stmt = $conn->prepare("SELECT * FROM ss_programs 
												WHERE program_id = :program_id");
  	$stmt->bindValue(':program_id', $program_id);
  	$stmt->execute();
	$program = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="_sta_assign_students.css">

<!-- Start Main Window -->
<div id="assign-table">
	<h2>Assign <?php print $program['program_code']; ?> Students</h2>
	<table class="pure-table pure-table-bordered assign-table">
		<tr>
			<th style="text-align: left">Student Name</th>
			<th>1</th>
			<th>2</th>
			<th>Accepted</th>
			<th>Status</th>
			<th>Time</th>
		</tr>
		<?php
		$j=0;
		$stmt = $conn->prepare("SELECT registration_id, registration_date, registration_timestamp, registration_time, student_name_last, student_name_common, 
													admin_paid, admin_prereq, admin_mf, admin_df, admin_if,
													accepted_session, selected_session1, selected_session2,  
													status_email_sent_accept, status_accept_read, status_accept_confirmed, waitlist
													FROM ss_registrations
													WHERE selected_program = :selected_program
													ORDER BY student_name_last, student_name_first");
	   $stmt->bindValue(':selected_program', $program_id);
		$stmt->execute();
		$result = $stmt->fetchAll();
					
		foreach ($result as $student)
		{?>
			<tr>
				<td id="td_name_<?php print $student['registration_id'];?>" 
					<?php if ($student['accepted_session'] != 0){print "class=\"accepted\"";} ?> style="text-align: left;">
					<a href="../student-edit/index.php?sid=<?php print $student['registration_id']; ?>">
						<?php 
						print $student['student_name_last'] . ", ". $student['student_name_common']." ";
						if ($student['admin_mf']==1)
							{print "<i style=\"color: #C03030;\" class=\"fa fa-cross\"></i>";}
						if ($student['admin_df']==1)
							{print "<i style=\"color: #F0F090;\" class=\"fa fa-cross\"></i>";}
						if ($student['admin_if']==1)
							{print "<i style=\"color: #6CBDFF;\" class=\"fa fa-cross\"></i>";}
						?>
					</a>
				</td>
				<?php
				$stmt = $conn->prepare("SELECT * FROM ss_sessions WHERE session_id = :session_id");
				$stmt->bindParam(':session_id', $student['selected_session1']);
				$stmt->execute();		
				$session = $stmt->fetch(PDO::FETCH_ASSOC);
				?>
				<td id="td_1_<?php print $student['registration_id'];?>" class="<?php print $student['selected_session1']; ?>
					<?php if ($student['selected_session1'] == $student['accepted_session'] AND $student['accepted_session'] != "0") {print " accepted";} ?>">
					<?php
						print $session['session_program_code']. " " .$session['session_number'];
					?>
				</td>
				<?php
				$stmt = $conn->prepare("SELECT * FROM ss_sessions WHERE session_id = :session_id");
				$stmt->bindParam(':session_id', $student['selected_session2']);
				$stmt->execute();		
				$session = $stmt->fetch(PDO::FETCH_ASSOC);
				?>
				<td id="td_2_<?php print $student['registration_id'];?>" class="<?php print $student['selected_session2']; ?>
					<?php if ($student['selected_session2'] == $student['accepted_session'] AND $student['accepted_session'] != "0") {print " accepted";} ?>">
					<?php
						print $session['session_program_code']. " " .$session['session_number'];
					?>	
				</td>	
				<td style="font-size: .9em;">
					<form class="pure-form pure-form-stacked">
						<input type=hidden name="registration_id" value="<?php print $student['registration_id'];?>">
						<select class="pure-input-1 session_input" style="margin: 0 auto; min-height: 35px;" name="accepted_session">
							<option value="0">-</option>
							<?php 
							$stmt = $conn->prepare("SELECT session_id, session_program_code, session_number FROM ss_sessions 
																		WHERE session_program_code = :session_program_code
																		ORDER BY session_number");
				  			$stmt->bindValue(':session_program_code', $program['program_code']);
							$stmt->execute();
							$sessions = $stmt->fetchAll();
										
							foreach ($sessions as $session)
							{
								print "<option style=\"padding: 5px;\" value=".$session['session_id'];
								if ($student['accepted_session'] == $session['session_id'])
								{print " selected";}
								print ">".$session['session_program_code']. " " .$session['session_number']."</option>";
							}
							?>
						</select>
					</form>
				</td>
				<?php
				$status="";
				$statuscolor="background-color: #FFFFFF; color: #222;";
				if ($student['status_email_sent_accept']=="1"){$status = "Emailed";$statuscolor="background-color: #C03030; color: #FFF;";}
				if ($student['status_accept_read']=="1"){$status = "Read Email";$statuscolor="background-color: #003366; color: #FFF;";}
				if ($student['status_accept_confirmed']=="1"){$status = "Confirmed";$statuscolor="background-color: #6BB46F; color: #FFF;";}
				?>
				<td style="<?php print $statuscolor;?>">
					<?php 
					if ($status != ""){print $status."<br />";}
					if ($student['waitlist'] != "") {print "WL: ".$student['waitlist'];}
					?>
				</td>
				<td id="td_time_<?php print $student['registration_id'];?>"
					<?php if ($student['accepted_session'] != 0){print "class=\"accepted\"";} ?> style="font-size: .7em;">
					<?php	print $student['registration_date']." ".$student['registration_time']; ?>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>

<?php include("sta-footer.php"); ?>

<script>

$(document).ready(function(){
	
	$(".session_input").on('input', function() {
    	$(this).parents("form").submit();
    	
    	return false;
  	});
	
	$("form").submit(function(e){
	   	e.preventDefault();
		var rid = $(this).find('input[name="registration_id"]').val();
		var sid = $(this).find('select[name="accepted_session"]').val();

		
		
	   	var senddata = $(this).serialize(); 	 	
 	 	//alert (senddata);return false;
 	 	
	    $.ajax({
	      type: "POST",
	      url: "process-assign-session.php",
	      data: senddata,
	      success: function() {
	      	if (sid == "0") {
				$("#td_name_" + rid).removeClass("accepted");
				$("#td_1_" + rid).removeClass("accepted");
				$("#td_2_" + rid).removeClass("accepted");
				$("#td_time_" + rid).removeClass("accepted");
			}
			else {
				$("#td_name_" + rid).addClass("accepted");
				if ($("#td_1_" + rid).hasClass(sid)) {$("#td_1_" + rid).addClass("accepted");}
				if ($("#td_2_" + rid).hasClass(sid)) {$("#td_2_" + rid).addClass("accepted");}
				$("#td_time_" + rid).addClass("accepted");
			}	
	      }           
	    }); 
	  });
	  
});

</script>