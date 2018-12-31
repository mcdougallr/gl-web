<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');
include ('../shared/eventfunctions.php');

$eventcount = 0;
if (isset($_GET['sid'])) {$staff_id = cleantext($_GET['sid']);}

?>
<style>
	#schedule .schedule-item {
		cursor: auto;
	}
</style>

<div id="schedule-box">
	<div class="schedule-label">
		<?php print date("F j, Y",strtotime($_SESSION['date'])); ?>
	</div>
	<div class="pure-g">
		<div class="pure-u-1 pure-u-md-1-2">		
			<?php
		
			//Include BD Schedule
			include ('print_schedule_birthday.php');
						
			//Include Task Schedule
			include ('print_schedule_tasks.php');
			
			//Separate into 2 columns on large screens
			if ($eventcount != 0) {
				print "</div><div class=\"pure-u-1 pure-u-md-1-2\">";	
			}		

			//Include Visit Schedule
			include ('print_schedule_visits.php');
			
			//Which of the generic schedules to include	
			$gen_types = array('E', 'F', 'S', 'O');
			include ('print_schedule_gen.php');
			
			//Include Summer Schedule
			include ('print_schedule_summer.php');
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

