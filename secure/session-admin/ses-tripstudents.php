<div>
	<form id="student-form-<?php print $extra; ?>" class="pure-form pure-form-stacked" method=post  style="margin-bottom: 5px;">
		<input type="hidden" name="tripstudent_extra" value="<?php print $extra; ?>" />
		<input type="hidden" name="tripstudent_trip_id" value="<?php print $trip_id; ?>" />
		<div>
			<label>Add Student</label>
			<select class="pure-input-1 option" name="tripstudent_student_id" id="student-picker-<?php print $extra; ?>">
				<option value="">Select...</option>
				<?php
					if ($extra == 0)
					{
						$studentquery = "SELECT registration_id, student_name_last, student_name_common FROM ss_registrations
														LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_registrations.accepted_session
														LEFT JOIN ss_session_sections ON ss_session_sections.section_session_id = ss_sessions.session_id
														WHERE section_id = '$section_id'
														ORDER BY student_name_last, student_name_first";
					}
					else
					{
						$studentquery = "SELECT registration_id, student_name_last, student_name_common FROM ss_registrations
														WHERE accepted_session = 15
														ORDER BY student_name_last, student_name_first";
					}
					foreach ($conn->query($studentquery) as $student)
					{
						print "<option value=".$student['registration_id'];
						print ">".$student['student_name_last'].", ".$student['student_name_common']."</option>";
					}
				?>
			</select>
		</div>
	</form>
	<div>
		<label>Current Students</label>
		<div id="student-updating-<?php print $extra; ?>" class="student-text">Updating...</div>
		<div id="student-info-<?php print $extra; ?>"  class="student-text"></div>
	</div>
</div>

<script>
   
	$(document).ready(function(){				
		
		$('#student-info-<?php print $extra; ?>').load('process-tripstudent-load.php',{"trip_id" : "<?php print $trip_id; ?>","extra": "<?php print $extra; ?>"},function() {
			$('#student-updating-<?php print $extra; ?>').fadeOut('fast');
			$('#student-info-<?php print $extra; ?>').fadeIn('fast');
		});
		
		$("#student-picker-<?php print $extra; ?>").change(function() {
			$("#student-form-<?php print $extra; ?>").submit();
		});
			
		$("#student-form-<?php print $extra; ?>").submit(function(e) {
			e.preventDefault();
			if ($("#student-picker-<?php print $extra; ?>").val() == "") {alert("Student name required.");return false;}
			$("#student-info-<?php print $extra; ?>").fadeOut("fast");
			$("#student-updating<?php print $extra; ?>").fadeIn("fast");
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			$.ajax({
				type : "POST",
				url : "process-tripstudent-input.php",
				data : senddata,
				success : function() 
				{
					$('#student-picker-<?php print $extra; ?>').prop('selectedIndex',0);
					$('#student-info-<?php print $extra; ?>').load('process-tripstudent-load.php',{"trip_id" : "<?php print $trip_id; ?>","extra": "<?php print $extra; ?>"},function() {
						$("#student-updating-<?php print $extra; ?>").fadeOut("fast");
						$('#student-info-<?php print $extra; ?>').fadeIn('fast');
					});
				}
			});
		});
		
	});
	
</script>