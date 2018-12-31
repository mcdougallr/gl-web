<div id="staff-info-block">
	<form id="staff-form-<?php print $tt; ?>" class="pure-form pure-form-stacked" method=post>
		<input type="hidden" name="tripstaff_tt" value=<?php print $tt; ?>>
		<input type="hidden" name="tripstaff_trip_id" value=<?php print $trip_id; ?>>
		<div style='text-align: left;' class="pure-g">
			<div class="pure-u-1 pure-u-sm-1-2">
				<label>Add Staff</label>
				<select class="pure-input-1" class="option" name="tripstaff_staff_id" id="staff-picker-<?php print $tt; ?>">
					<option value="">Select...</option>
					<?php
						$staffquery = "SELECT staff_id, staff_sex, staff_name_last, staff_name_common
													FROM staff_workdays
													LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
													LEFT JOIN schedule_summer ON schedule_summer.summer_id = schedule_events.event_type_id
													LEFT JOIN staff ON staff_workdays.workday_staff_id = staff.staff_id 
													WHERE summer_section_id = '$section_id' AND event_type = 'X'
													GROUP BY staff_id
													ORDER BY staff_sex, staff_name_last, staff_name_first";
						foreach ($conn->query($staffquery) as $staff)
						{
							print "<option value=".$staff['staff_id'];
							print ">".$staff['staff_name_last'].", ".$staff['staff_name_common']."</option>";
						}
					?>
				</select>
			</div>
			<div class="pure-u-1-4 pure-u-sm-1-8" style="text-align: center;"><i id="staff-submit-<?php print $tt; ?>" class="fa fa-plus"></i></div>
		</div>
	</form>
	<div>
		<label>Current Staff</label>
		<div id="staff-updating-<?php print $tt; ?>">Updating...</div>
		<div id="staff-info-<?php print $tt; ?>" class="pure-u-1">
		</div>
	</div>
</div>

<script>
   
	$(document).ready(function(){				
		
		$('#staff-info-<?php print $tt; ?>').load('process-load-staff.php',{"trip_id" : "<?php print $trip_id; ?>","tt": "<?php print $tt; ?>"},function() {
			$('#staff-info-<?php print $tt; ?>').fadeIn('fast');
		});
		
		$("#staff-submit-<?php print $tt; ?>").click(function() {
			$("#staff-form-<?php print $tt; ?>").submit();
		});
			
		$("#staff-form-<?php print $tt; ?>").submit(function(e) {
			e.preventDefault();
			if ($("#staff-picker-<?php print $tt; ?>").val() == "") {alert("Staff name required.");return false;}
			$("#staff-info-<?php print $tt; ?>").fadeOut("fast");
			$("#staff-updating<?php print $tt; ?>").fadeIn("fast");
			var senddata = $(this).serialize();
			alert(senddata);return false;
			$.ajax({
				type : "POST",
				url : "process-staff-input.php",
				data : senddata,
				success : function() 
				{
					$('#staff-picker').prop('selectedIndex',0);
					$('#staff-info').load('process-load-staff.php',{"trip_id" : "<?php print $trip_id; ?>","tt": "<?php print $tt; ?>"},function() {
						$("#staff-updating-<?php print $tt; ?>").fadeOut("fast");
						$('#staff-info-<?php print $tt; ?>').fadeIn('fast');
					});
				}
			});
		});
		
	});
	
</script>