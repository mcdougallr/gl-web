<script>
   
	$(document).ready(function(){				
		
		// SET REFER PATH
		refer = "<?php print $_SESSION['refer']; ?>";
		if (refer == "sya") {page = "../school-year-admin/index.php";}
		else if (refer == "log") {page = "../logistics/index.php";}
		else if (refer == "sa") {page = "../staff-admin/index.php";}
		else if (refer == "staff") {page = "../staff/index.php";}
		else if (refer == "sy") {page = "../school-year/index.php";}
				
		$('#staff-info').load('process-load-staff.php',{"event_id" : "<?php print $event_id; ?>"},function() {
			$('#staff-info').fadeIn('fast');
		});
		
		$("#staff-submit").click(function() {
			$("#staff-form").submit();
		});
			
		$("#staff-form").submit(function(e) {
			e.preventDefault();
			if ($("#staff-picker").val() == "") {alert("Staff name required.");return false;}
			$("#staff-info").fadeOut("fast");
			$("#staff-updating").fadeIn("fast");
			var senddata = $(this).serialize();
			//alert(senddata);return false;
			$.ajax({
				type : "POST",
				url : "process-staff-input.php",
				data : senddata,
				success : function() 
				{
					$('#staff-picker').prop('selectedIndex',0);
					$('#staff-info').load('process-load-staff.php',{"event_id" : "<?php print $event_id; ?>"},function() {
						$("#staff-updating").fadeOut("fast");
						$('#staff-info').fadeIn('fast');
					});
				}
			});
		});
		
		// PROCESS FORM INPUT
		$('.gl-input').change(function(e) {
			field_name = $(this).attr("name");
			field_val = $(this).val();
			$(this).css('background', '#39F');
			//alert(field_name+" "+field_val);
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					field_name : field_name,
					field_val : field_val,
					table_name : "schedule_<?php print $type_name; ?>",
					id_name : "<?php print $type_name; ?>_id",
					id_val: "<?php print $event['event_type_id']; ?>"
				},
				success : function() {
					$('.gl-input').delay(300)
				    .queue(function() {
				        $('.gl-input').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
		$('.gl-input-date').change(function(e) {
			field_val = $(this).val();
			$(this).css('background', '#39F');
			//alert(field_val);
			$.ajax({
				type : "POST",
				url : "process-input-event-date.php",
				data : {
					field_val : field_val,
					event_id: "<?php print $event_id; ?>"
				},
				success : function() {
					$('.gl-input-date').delay(300)
				    .queue(function() {
				        $('.gl-input-date').css('background', 'transparent').dequeue();
				    });
				}
			});
		});
		
		$("#complete").click(function (e) {
			e.preventDefault();
			window.setTimeout(function(){window.location.href = page;},250);
		});
		
		$(".textarea_expand").on("keyup", function() {
			this.style.height = "1px";
			this.style.height = (this.scrollHeight) + "px";
		});
		
		$("#delete").click(function (e) {
			e.preventDefault();
			$.ajax({
				type : "POST",
				url : "process-edit-delete.php",
				data : {
					type : "<?php print $type_name; ?>",
					type_day_id: "<?php print $event['event_type_id']; ?>",
					event_id: "<?php print $event_id; ?>"
				},
				success : function() {
					window.location.href = page;
				}
			});
		});
		
	});

</script>