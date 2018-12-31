<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/eventfunctions.php');

$count = 0;
?>

<div id="schedule-box">
	<div class="schedule-label">
		<?php print date("F j, Y",strtotime($_SESSION['date'])); ?>
	</div>
	<div id="new-buttons">
		<i id="i" class="fa fa-info-circle add-button"></i>
		<i id="b" class="fa fa-bus add-button"></i>
		<i id="d" class="fa fa-road add-button"></i>
		<i id="e" class="fa fa-cog add-button"></i>
		<i id="f" class="fa fa-cutlery add-button"></i>
		<i id="s" class="fa fa-eye add-button"></i>
		<i id="o" class="fa fa-desktop add-button"></i>
	</div>
	<div class="pure-g">
		<div class="pure-u-1 pure-u-md-1-2">		
			<?php
			$eventcount = 0;
			
			//Include Info Schedule
			include ('print_schedule_info.php');
			
			//Include Bus Schedule
			include ('print_schedule_busses.php');
			
			//Include Drive Schedule
			include ('print_schedule_drives.php');
			
			//Separate into 2 columns on large screens
			if ($eventcount != 0) {
				print "</div><div class=\"pure-u-1 pure-u-md-1-2\">";	
			}
			
			//Which of the generic schedules to include	
			$gen_types = array('E', 'F', 'S', 'O');
			include ('print_schedule_gen.php');
			
			?>
		</div>
	</div>
	<?php
	if ($eventcount == 0)
	{?>
		<div class="pure-g schedule-item">
			<div class="pure-u-1-12 icon"><i style="color: #003;" class="fa fa-info-circle"></i></div>
			<div class="pure-u-11-12">
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
			if (log == "b") {url = "../schedule/edit-bus.php?eid=";}
			else if (log == "d") {url = "../schedule/edit-drive.php?eid=";}
			else if (log == "i") {url = "../schedule/edit-info.php?eid=";}
			else if (log == "g") {url = "../schedule/edit-gen.php?eid=";}
			//alert(url);
			value = id.substring(7);
			//alert(value);
			window.location.href = url+value;
		});
		$('.add-button').click(function() {
			log = $(this).attr("id");
			if (log == "b") {url = "../schedule/edit-bus.php";}
			else if (log == "d") {url = "../schedule/edit-drive.php";}
			else if (log == "i") {url = "../schedule/edit-info.php";}
			else if (log == "e") {url = "../schedule/edit-gen.php?etype=E";}
			else if (log == "f") {url = "../schedule/edit-gen.php?etype=F";}
			else if (log == "s") {url = "../schedule/edit-gen.php?etype=S";}
			else if (log == "o") {url = "../schedule/edit-gen.php?etype=O";}
			//alert(url);
			window.location.href = url;
		});
	});
</script>
