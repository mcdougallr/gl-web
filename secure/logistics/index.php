<?php 	
	include ("log-header.php"); 
?>

<link rel="stylesheet" href="_index.css">

<section id="day-calendar" class="page">
    <div class="pure-g">
      	<div id="calendar" class="pure-u-1 pure-u-sm-1-2 pure-u-md-1-3"></div>
      	<div id="schedule" class="pure-u-1 pure-u-sm-1-2 pure-u-md-2-3"></div>
    </div>
</section>
	
<?php include("log-footer.php"); ?>

<script>
	$(document).ready(function() {
		$('#calendar').load('../calendar/cal-calendar.php?r=log', function() {
			$('#schedule').load('../schedule/cal-schedule-log.php', function() {
			});
		});
	});
</script>