<div>
	<form id="itinerary-form" class="pure-form pure-form-stacked" method=post  style="margin-bottom: 5px;">
		<input type="hidden" name="day_trip_id" value="<?php print $trip_id; ?>" />
		<div class="pure-g">
			<div class="pure-u-1-4">
				<label>Day</label>
				<select class="pure-input-1 option" name="day_num" id="day_num">
					<option value="">#</option>
					<?php
						$x = 1;
						while ($x < 22){print "<option>".$x."</option>";$x++;}
					?>
				</select>
			</div>
			<div class="pure-u-3-4">
				<label> Date</label>
				<input name="day_date" type="date" id="day_date" />
			</div>
			<div class="pure-u-3-4">
				<label>Location</label>
				<input name="day_loc" type="text"  id="day_loc" />
			</div>
			<div class="pure-u-1-4">
				<i id="itinerary-submit" style=" vertical-align: -35px;margin-left: 10px;color: #685191;cursor: pointer;" class="fa fa-plus"></i>
			</div>
		</div>
	</form>
	<div>
		<label>Current Itinerary</label>
		<div id="itinerary-updating" class="day-text">Updating...</div>
		<div id="itinerary-info"  class="day-text"></div>
	</div>
</div>

<script>
   
	$(document).ready(function(){				
		
		$('#itinerary-info').load('process-tripday-load.php',{"trip_id" : "<?php print $trip_id; ?>"},function() {
			$('#itinerary-updating').fadeOut('fast');
			$('#itinerary-info').fadeIn('fast');
		});
		
		$("#itinerary-submit").click(function() {
			$("#itinerary-form").submit();
		});
			
		$("#itinerary-form").submit(function(e) {
			e.preventDefault();
			if ($("#day_num").val() == "") {alert("Day number required.");return false;}
			if ($("#day_date").val() == "") {alert("Date required.");return false;}
			if ($("#day_loc").val() == "") {alert("Location required.");return false;}
			$("#itinerary-info").fadeOut("fast");
			$("#itinerary-updating").fadeIn("fast");
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			$.ajax({
				type : "POST",
				url : "process-tripday-input.php",
				data : senddata,
				success : function() 
				{
					$('#day_num').prop('selectedIndex',0);
					$('#day_date').val("");
					$('#day_loc').val("");
					$('#itinerary-info').load('process-tripday-load.php',{"trip_id" : "<?php print $trip_id; ?>"},function() {
						$("#itinerary-updating").fadeOut("fast");
						$('#itinerary-info').fadeIn('fast');
					});
				}
			});
		});
		
	});
	
</script>