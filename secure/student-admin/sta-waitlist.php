<?php 
	$page_title = "GL Waitlists";
	include ("sta-header.php"); 
?> 

<link rel="stylesheet" href="_sta-waitlist.css">

<h1>Session Waitlists</h1>
<div id="waitlist-container">
	<div id="enter-student" class="session-list">
		<h2>Add Student to Waitlist</h2>
		<form id="enter-student-form" class="pure-form pure-form-stacked" method="" action="">
			<div class="input-padding">
				<label>Session</label>
				<select class="pure-input-1" class="option" name="waitlist_session_id" id="session-picker">
					<option value="">Select...</option>
					<?php
					$sessionquery = "SELECT * FROM ss_sessions WHERE session_program_code != 'ST' ORDER BY session_sortorder";
					foreach ($conn->query($sessionquery) as $session)
					{print "<option value=\"".$session['session_id']."\">".$session['session_program_code'].$session['session_number']."</option>";}
					?>
				</select>
			</div>
			<div class="input-padding">
				<label>Student</label>
				<select class="pure-input-1" class="option" name="waitlist_student_id" id="student-picker">
					<option value="">Select...</option>
					<?php
					$studentquery = "SELECT registration_id, student_name_last, student_name_common FROM ss_registrations ORDER BY student_name_last";
					foreach ($conn->query($studentquery) as $student)
					{print "<option value=\"".$student['registration_id']."\">".$student['student_name_last'].", ".$student['student_name_common']."</option>";}
					?>
				</select>
			</div>
			<button id="waitlist_submit" class="plaintext-button" type="submit">submit</button>
		</form>
	</div>
	<?php
	foreach ($conn->query($sessionquery) as $session)
	{?>
		<div class="session-list">
			<h2><?php print $session['session_program_code'].$session['session_number']; ?></h2>
			<div id="sl-<?php print $session['session_id'];?>">
				<script>$(document).ready(function(){$("#sl-<?php print $session['session_id'];?>").load('process-waitlist-load-session.php',{"session_id" : "<?php print $session['session_id'];?>"});});</script>
			</div>
		</div>
	<?php
	}
	?>
</div>

<?php include("sta-footer.php"); ?>

<script>
	$(document).ready(function(){
		
		$("#enter-student-form").submit(function(e) {
			e.preventDefault();
			if ($("#session-picker").val() == "") {alert("Session required.");return false;}
			if ($("#student-picker").val() == "") {alert("Student required.");return false;}
			session_id = $("#session-picker").val();
			id = "#sl-" + session_id;
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			$.ajax({
				type : "POST",
				url : "process-waitlist-input.php",
				data : senddata,
				success : function() 
				{
					$('#session-picker').prop('selectedIndex',0);
					$('#student-picker').prop('selectedIndex',0);
					$(id).load('process-waitlist-load-session.php',{"session_id" : session_id});
				}
			});
		});
		
		$('body').on('click','.del-student',function(e){
			e.preventDefault();
			var id = this.id;
			var waitlist_id = id.replace("stu-", "");
			//alert(id); //return false;
			var session_id = $(this).parent().attr('class');
			id = "#sl-" + session_id;
			//alert(session_id); //return false;
			$.ajax({
				type : "POST",
				url : "process-waitlist-delete-student.php",
				data : {waitlist_id : waitlist_id},
				success : function() 
				{
					$(id).load('process-waitlist-load-session.php',{"session_id" : session_id});
				}
			});
			return false;
		});
		
	});
</script>