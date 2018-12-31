<?php
    session_start();

	include ('../shared/dbconnect.php');
	include ('../shared/clean.php');
	include ('../shared/eventfunctions.php');
	
	if (isset($_GET['r'])) {$refer = cleantext($_GET['r']);}
	else {$refer = "s";}
	
	if (isset($_GET['sid'])) {$staff_id = cleantext($_GET['sid']);}
	else {$staff_id = $_SESSION['gl_staff_id'];}
	
	if (!isset($_SESSION['date'])) {$_SESSION['date'] = date("Y-m-d");}

    $v_day = date("d", strtotime($_SESSION['date']));
    $v_month = date("m", strtotime($_SESSION['date']));
    $v_year = date("Y", strtotime($_SESSION['date']));

    $month_begin_date = $v_year . "-" . $v_month . "-01";
?>

<link rel="stylesheet" href="../calendar/_gl-cal.css">

<div id="calendar-box">
    <div style="float: left;">
        <button id="jump-today" class="cal-options">
            <i class="fa fa-calendar"></i>TODAY
        </button>
    </div>
    <div style="float: right;">
        <button id="jump-date" class="cal-options">
            JUMP DATE<i class="fa fa-calendar"></i>
        </button>
    </div>
    <div id="jump-date-input">
    <form id="jump-date-form" style="margin-bottom: 0; padding: 0;" method=post class="pure-form pure-form-stacked" method="post" action="">
      <input id="jump-date-select" type="date" value="<?php print $_SESSION['date']; ?>"/>
    </form>
	</div>
	<div style="clear: both;"></div>
	<div style="text-align: center;">
		<button id="prev" class="arrow-button"><i class="fa fa-caret-left"></i></button>
		<div class="month-label" style="display:inline-block;"><?php print date("F", strtotime($_SESSION['date'])); ?></div>
  	<button id="next" class="arrow-button" style="display:inline-block;"><i class="fa fa-caret-right"></i></button>
	</div>
	<table>
  	<tr> 
    	<th>S</th>
    	<th>M</th>
    	<th>T</th>
    	<th>W</th>
    	<th>T</th>
    	<th>F</th>
    	<th>S</th>
  	</tr>
    <?php 
  		$month_begin = date("w",strtotime($month_begin_date));
		$month_length = date("t",strtotime($_SESSION['date']));
      $z=0;
	  
      for($i= 1 - $month_begin; $i<$month_length+1; $i++)
      	{
	        if($z==0)
        	{print "<tr>";}
				if($i<1)
        	{print "<td></td>";}
      	else
      	{
      		$calendardate = $v_year."-".$v_month."-" .$i;
			$eventcount = 0;
			if ($refer == "s" OR $refer == "sa")
			{
			  	$infoevents = getevents("I", $calendardate, $conn);
				$eventcount = $eventcount + count($infoevents);

				$gen_types = array('X', 'V', 'T', 'D', 'E', 'F', 'S', 'O');
		   
				foreach($gen_types as $type)
				{
					$events = getstaffeventscount($type, $staff_id, $calendardate, $conn);
					$eventcount = $eventcount + count($events);
				}			
			}
			if ($refer == "log")
			{
				$gen_types = array('I', 'B', 'D', 'E', 'F', 'S', 'O');
		   
				foreach($gen_types as $type)
				{
					$events = geteventcount($type, $calendardate, $conn);
					$eventcount = $eventcount + count($events);
				}
			}
			if ($refer == "sy" OR $refer == "sya")
			{
				$gen_types = array('I', 'V', 'T');
		   
				foreach($gen_types as $type)
				{
					$events = geteventcount($type, $calendardate, $conn);
					$eventcount = $eventcount + count($events);
				}
			}
		
          	print "<td>";
        	if ($eventcount > 0) {print "<button class=\"cal-day busy-day";}
			else {print "<button class=\"cal-day empty-day";}
					
			if ($v_day == $i) {print " active-day";}
			print "\" id=\"day_".$i."\">".$i;
			print "</button></td>";
    	?>
					
				<?php
                    }
                    $z++;
                    if($z==7)
                    {print "</tr>";
                    $z=0;}
                    }
                    if($z!=7 and $z!=0)
                    {
                    for($i=0; $i<(7-$z); $i++)
                    {
                    print "<td></td>";
                    }
                    print "</tr>";
                    }
        ?>
  	</table>
  	<div style="text-align: center;">
        <a id="print-month" href="../calendar/cal-print-month.php?r=
			<?php 
				print $refer;
				if ($refer == "log") {print "&logt=all";}
				elseif ($refer == "s") {print "&sid=".$staff_id;}
				elseif ($refer == "sa") {print "&sid=".$staff_id;}
			?>" target="_blank" class="cal-options">VIEW MONTH<i class="fa fa-print"></i>
        </a>
    </div>
</div>

<script>
	
	// JQUERY DATEPICKER
	(function() {  
		var elem = document.createElement('input');  
   		elem.setAttribute('type', 'date');  
	    if ( elem.type === 'text' ) {  
	       $('#jump-date-select').datepicker({dateFormat: 'yy-mm-dd'}); 
	    }  
 	})();

	$(document).ready(function(){
		
		// SET SCHEDULE PATH
		refer = "<?php print $refer; ?>";
		if (refer == "s" || refer == "sa") {page = "../schedule/cal-schedule-s.php?sid=<?php print $staff_id; ?>";}
		else if (refer == "log") {page = "../schedule/cal-schedule-log.php";}
		else if (refer == "sy" || refer == "sya") {page = "../schedule/cal-schedule-sy.php";}
		
		// SHOW DAY
		$('.cal-day').click(function() {
			id = $(this).attr("id");
			day = id.replace("day_", "");
			id = "#" + id;
			//alert(page);return false;
			$("#working").fadeIn("fast",function(){
				$('.cal-day').removeClass("active-day");
				$(id).addClass("active-day");
				$.ajax({
					type: "POST",
					url: "../calendar/cal-process-date.php",
					data: {day: day}, 
					success: function() {
						$('#schedule').load(page, '', function() {
							$('#schedule').fadeIn('fast');
							$("#working").fadeOut("fast");
						});						
					}
				});
			});
		});

		// JUMP TO TODAY
		$('#jump-today').click(function() {
			$('#working').fadeIn('fast');
			date = '<?php print date('Y-m-d'); ?>';
			$.ajax({
				type: "POST",
				url: "../calendar/cal-process-date.php",
				data : {date : date},
				success: function()
				{
					$('#schedule').load(page);
					$("#calendar").load("../calendar/cal-calendar.php?r=<?php print $refer; ?>&sid=<?php print $staff_id; ?>",'',function(){
						$("#calendar").fadeIn("fast");
						$("#schedule").fadeIn("fast");
						$("#working").fadeOut("fast");
					});					
				}
			});
		});

		// JUMP TO DATE
		$('#jump-date').click(function() {
			$('#jump-date-input').toggle();});
			$('#jump-date-select').change(function() {
				$('#working').fadeIn('fast');
				date = $('#jump-date-select').val();
				$.ajax({
					type: "POST",
					url: "../calendar/cal-process-date.php",
					data : {date : date},
					success: function()
					{
						$('#schedule').load(page);
						$("#calendar").load("../calendar/cal-calendar.php?r=<?php print $refer; ?>&sid=<?php print $staff_id; ?>",'',function(){
							$("#calendar").fadeIn("fast");
							$("#schedule").fadeIn("fast");
							$("#working").fadeOut("fast");
						});		
					}
				});
			});

			// PREVIOUS/NEXT MONTH
			$('#prev').click(function() {
				$('#working').fadeIn('fast');
				$.ajax({
					type: "POST",
					url: "../calendar/cal-process-prev.php",
					success: function()
					{
						$('#schedule').load(page);
						$("#calendar").load("../calendar/cal-calendar.php?r=<?php print $refer; ?>&sid=<?php print $staff_id; ?>",'',function(){
							$("#calendar").fadeIn("fast");
							$("#schedule").fadeIn("fast");
							$("#working").fadeOut("fast");
						});
					}		
				});
			});

			$('#next').click(function() {
				$('#working').fadeIn('fast');
				$.ajax({
					type: "POST",
					url: "../calendar/cal-process-next.php",
					success: function()
					{
						$('#schedule').load(page);
						$("#calendar").load("../calendar/cal-calendar.php?r=<?php print $refer; ?>&sid=<?php print $staff_id; ?>",'',function(){
							$("#calendar").fadeIn("fast");
							$("#schedule").fadeIn("fast");
							$("#working").fadeOut("fast");
						});		
					}
				});
			});
			
		});
</script>