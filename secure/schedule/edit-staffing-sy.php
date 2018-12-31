<label>Staffing</label>
<div id="staff-info-block">
	<form id="staff-form" class="pure-form pure-form-stacked" method=post>
		<input type="hidden" name="workday_event_id" value="<?php print $event_id; ?>">
		<div style='text-align: left;' class="pure-g">
			<div class="pure-u-1 pure-u-sm-1-2">
				<label>Add Staff</label>
				<select class="pure-input-1" class="option" name="workday_staff_id" id="staff-picker">
					<option value="">Select...</option>
					<?php
						$staffquery = "SELECT staff_id, staff_name_last, staff_name_common FROM staff WHERE admin_archive != 'Yes' AND staff_access > 1 ORDER BY staff_name_last";
						foreach ($conn->query($staffquery) as $staff)
						{
							print "<option value=".$staff['staff_id'];
							print ">".$staff['staff_name_last'].", ".$staff['staff_name_common']."</option>";
						}
					?>
				</select>
			</div>
			<div class="pure-u-3-4 pure-u-sm-3-8">
				<label>%</label>
				<select class="pure-input-1" class="option" name="workday_percentage">
					<option value="1.0">1.0</option>
					<option value="0.5">0.5</option>
					<option value="0.0">0.0</option>
				</select>
			</div>
			<div class="pure-u-1-4 pure-u-sm-1-8" style="text-align: center;"><i id="staff-submit" class="fa fa-plus"></i></div>
		</div>
	</form>
	<div>
		<label>Current Staff</label>
		<div id="staff-updating">Updating...</div>
		<div id="staff-info" class="pure-u-1">
		</div>
	</div>
</div>