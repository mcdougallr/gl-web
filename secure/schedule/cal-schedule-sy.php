<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');
include ('../shared/eventfunctions.php');

$eventcount = 0;

?>

<div id="schedule-box">
	<div class="schedule-label">
		<?php print date("F j, Y",strtotime($_SESSION['date'])); ?>
	</div>
	<div id="new-buttons">
		<i id="i" class="fa fa-info-circle add-button"></i>
		<i id="t" class="fa fa-bug add-button"></i>	
		<i id="v" class="fa fa-tree add-button"></i>
	</div>
	<div class="pure-g">
		<div class="pure-u-1 pure-u-md-1-2">		
			<?php
		
			//Include BD Schedule
			include ('print_schedule_birthday.php');
			
			//Include Info Schedule
			include ('print_schedule_info.php');
			
			//Include Task Schedule
			include ('print_schedule_tasks.php');
			
			//Separate into 2 columns on large screens
			if ($eventcount != 0) {
				print "</div><div class=\"pure-u-1 pure-u-md-1-2\">";	
			}		

			//Include Visit Schedule
			include ('print_schedule_visits.php');
			?>
		</div>
	</div>
	<?php
	if ($eventcount == 0)
	{?>
		<div class="pure-g schedule-item">
			<div class="pure-u-1-12 pure-u-md-1-24 icon"><i style="color: #003;" class="fa fa-info-circle"></i></div>
			<div class="pure-u-11-12 pure-u-md-11-24">
				<div class="details">
					<div class="title">Currently no items scheduled for today.</div>
				</div>
			</div>
		</div>
	<?php
	}	
	?>
</div>

<script>
	$(document).ready(function() {
		$('.schedule-item').click(function() {
			id = $(this).attr("id");
			log = id.charAt(0);
			if (log == "i") {url = "../schedule/edit-info.php?eid=";}
			else if (log == "v") {url = "../schedule/edit-visit.php?eid=";}
			else if (log == "t") {url = "../schedule/edit-task.php?eid=";}
			//alert(url);
			value = id.substring(7);
			//alert(value);
			window.location.href = url+value;
		});
		$('.add-button').click(function() {
			log = $(this).attr("id");
			if (log == "i") {url = "../schedule/edit-info.php";}
			else if (log == "v") {url = "../schedule/edit-visit.php";}
			else if (log == "t") {url = "../schedule/edit-task.php";}
			//alert(url);
			window.location.href = url;
		});
	});
</script>
