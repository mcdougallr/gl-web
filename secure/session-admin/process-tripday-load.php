<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$trip_id = cleantext($_POST['trip_id']);

$stmt = $conn -> prepare("SELECT * FROM fp_days
												WHERE day_trip_id = :day_trip_id
												ORDER BY day_num");
$stmt -> bindValue(':day_trip_id', $trip_id);
$stmt -> execute();
$itinerarylist = $stmt->fetchAll();

if ($itinerarylist != NULL) {
	foreach ($itinerarylist as $day)
	{
		print "<div id=\"tripday_".$day['day_id']."\" class=\"\">";
		print $day['day_num']." - ".$day['day_loc']." (".$day['day_date'].")";
		print "<i id=\"".$day['day_id']."\" class=\"fa fa-times delete-tripday\"></i></div>";
	}
}
else
{
	print "<div><i class=\"fa fa-angle-double-left\"></i> No trip days <i class=\"fa fa-angle-double-right\"></i></div>";
}

?>
<script>
   
	$(document).ready(function(){				
		
		// PROCESS FORM INPUT
		$('.delete-tripday').click(function(e) {
			id = $(this).attr("id");
			del_id = "#tripday_" + id;
			//alert(id+" "+del_id);
			$.ajax({
				type : "POST",
				url : "process-tripday-delete.php",
				data : {day_id : id},
				success : function() {
					$('#itinerary-info').load('process-tripday-load.php',{"trip_id" : "<?php print $trip_id; ?>"},function() {
						$('#itinerary-info').fadeIn('fast');
					});
				}
			});
		});
		
	});

</script>