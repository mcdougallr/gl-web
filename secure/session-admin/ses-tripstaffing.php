<div>
	<form id="staff-form-<?php print $tt; ?>" class="pure-form pure-form-stacked" method=post  style="margin-bottom: 5px;">
		<input type="hidden" name="tripstaff_tt" value="<?php print $tt; ?>" />
		<input type="hidden" name="tripstaff_trip_id" value="<?php print $trip_id; ?>" />
		<div>
			<label>Add Staff</label>
			<select class="pure-input-1 option" name="tripstaff_staff_id" id="staff-picker-<?php print $tt; ?>">
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
	</form>
	<div>
		<label>Current Staff</label>
		<div id="staff-updating-<?php print $tt; ?>" class="staff-text">Updating...</div>
		<div id="staff-info-<?php print $tt; ?>"  class="staff-text"></div>
	</div>
</div>

<script>
   
	$(document).ready(function(){				
		
		$('#staff-info-<?php print $tt; ?>').load('process-tripstaff-load.php',{"trip_id" : "<?php print $trip_id; ?>","tt": "<?php print $tt; ?>"},function() {
			$('#staff-updating-<?php print $tt; ?>').fadeOut('fast');
			$('#staff-info-<?php print $tt; ?>').fadeIn('fast');
		});
		
		$("#staff-picker-<?php print $tt; ?>").change(function() {
			$("#staff-form-<?php print $tt; ?>").submit();
		});
			
		$("#staff-form-<?php print $tt; ?>").submit(function(e) {
			e.preventDefault();
			if ($("#staff-picker-<?php print $tt; ?>").val() == "") {alert("Staff name required.");return false;}
			$("#staff-info-<?php print $tt; ?>").fadeOut("fast");
			$("#staff-updating<?php print $tt; ?>").fadeIn("fast");
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			$.ajax({
				type : "POST",
				url : "process-tripstaff-input.php",
				data : senddata,
				success : function() 
				{
					$('#staff-picker-<?php print $tt; ?>').prop('selectedIndex',0);
					$('#staff-info-<?php print $tt; ?>').load('process-tripstaff-load.php',{"trip_id" : "<?php print $trip_id; ?>","tt": "<?php print $tt; ?>"},function() {
						$("#staff-updating-<?php print $tt; ?>").fadeOut("fast");
						$('#staff-info-<?php print $tt; ?>').fadeIn('fast');
					});
				}
			});
		});
		
	});
	
</script>