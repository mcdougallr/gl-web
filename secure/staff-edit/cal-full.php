<?php
	session_start();
	
	include ('../shared/dbconnect.php');
	include ('../shared/functions.php');
	
	if (isset($_SESSION['date'])) 
	{
		$year = date("Y",strtotime(($_SESSION['date'])));
		$month = date("m",strtotime(($_SESSION['date'])));
	}	
	else 
	{
		$year = 2000;
		$month = 01;
	}
	
	$firstday = $year."-".$month."-01";
	$_SESSION['date'] = $firstday;
	$month_begin = date("w",strtotime(($firstday)));
	$month_length = date("t",strtotime($firstday));		
?>
<form id="cal-form" style="text-align: center;margin-bottom: 0;" method=post class="pure-form pure-form-stacked" method="post" action="">
	<select id="cal-month" name="cal-month" style="display: inline-block;">
        <?php
		for ($i = 1; $i < 13; $i++)
        {
          	$m = date("F", mktime(0, 0, 0, $i, 10));
         	print "<option value=" . sprintf("%02s", $i);
         	if ($m == date("F", strtotime($_SESSION['date']))) {print " selected";}
            print ">" . $m . "</option>";
      }
        ?>
	</select>
    <select id="cal-year" name="cal-year" style="display: inline-block;">
    	<?php
      	$y = date("Y");
     for ($i = $y - 2; $i < $y + 3; $i++)
      	{
        	print "<option";
        if ($i == date("Y", strtotime($_SESSION['date']))) {print " selected";}
        	print ">" . $i . "</option>";
     }
    	?>
  	</select>
</form>
<hr id="h1-line" style="margin: 0;"/>


<h2><?php print date("F Y",strtotime($firstday)); ?><i id="print-cal-button" class="fa fa-print"></i></h2>
<table>
	<tr>
		<th>Sunday</th>
		<th>Monday</th>
		<th>Tuesday</th>
		<th>Wednesday</th>
		<th>Thursday</th>
		<th>Friday</th>
		<th>Saturday</th>
	</tr>
	<?php
	$z=0;
	for($i= 1 - $month_begin; $i<$month_length+1; $i++)
	{
	  if($z==0)
	  {print "<tr>";}
	  if($i<1)
	  {print "<td bgcolor=\"#CCC\">&nbsp;</td>";}
	  else
	  {
	    $bgcolor="#FFFFFF";
	    
	    $calendardate = $year."-".$month."-".$i;
	    
	    print "<td>";
	    print "<div class=calendardate>".$i."</div>";
	    
		$stmt = $conn->prepare("SELECT gl_staff_workdays.*, gl_staff.admin_summer_confirmed, gl_staff_sessions.*, gl_staff_session_days.* FROM gl_staff_workdays 
												LEFT JOIN gl_staff ON gl_staff.staff_id = gl_staff_workdays.workday_staff_id
		          								LEFT JOIN gl_staff_sessions ON gl_staff_workdays.workday_session = gl_staff_sessions.staffing_session_id
		          								LEFT JOIN gl_staff_session_days ON gl_staff_workdays.workday_sd_id = gl_staff_session_days.sd_id
		          								WHERE workday_date= :calendardate AND workday_staff_id = :staff_id");
		$stmt->bindValue(':calendardate', $calendardate);
		$stmt->bindValue(':staff_id', $_SESSION['staff_id']);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$count = count($result);
			
		if ($count != 0)
		{
			foreach ($result as $row)
		    {?>
				<div class="title center"><?php print $row['staffing_session_program_code'] .$row['staffing_session_number']." (".$row['workday_percentage'].")";?></div>
				<div class="info center"><?php print $row['sd_description'];?></div>
				<div class="notes center"><?php print $row['workday_notes'];?></div>
				<br />
			<?php 
			} 
		}	
	
			$stmt = $conn->prepare("SELECT * FROM fws_workdays 
	            								LEFT JOIN fws_visits ON fws_visits.visit_id = fws_workdays.visit_id
	            								LEFT JOIN fws_schools ON fws_schools.school_id = fws_visits.school_id
	            								LEFT JOIN fws_programs ON fws_programs.program_id = fws_visits.program_id
	            								WHERE workday_date= :calendardate AND workday_staff_id = :staff_id AND fws_workdays.visit_id != 0");
			$stmt->bindValue(':calendardate', $calendardate);
			$stmt->bindValue(':staff_id', $_SESSION['staff_id']);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$count = count($result);
			
			if ($count != 0)
		    {
				foreach ($result as $row)
				{?>
					<div class="title center"><?php print $row['program_name'];?></div>
					<div class="info center"><?php print $row['teacher_name']." - ".$row['grade'];?></div>
					<div class="info center"><?php print $row['visit_notes'];?></div>
					<br />
				<?php
				}
			}
	
			$stmt = $conn->prepare("SELECT * FROM fws_workdays 
            								LEFT JOIN fws_tasks ON fws_tasks.task_id = fws_workdays.task_id
            								LEFT JOIN fws_programs ON fws_programs.program_id = fws_tasks.program_id
            								WHERE workday_date= :calendardate AND workday_staff_id = :staff_id AND fws_workdays.task_id != 0");
		   $stmt->bindValue(':calendardate', $calendardate);
		   $stmt->bindValue(':staff_id', $_SESSION['staff_id']);
		   $stmt->execute();
			$result = $stmt->fetchAll();
			$count = count($result);
			
			if ($count != 0)
		    {
				foreach ($result as $row)
				{?>
					<div class="title center"><?php print $row['program_name'];?></div>
					<div class="info center"><?php print $row['task_notes'];?></div>
					<br />
				<?php
				}
			}
	
	    print "</td>";
	  }
	  $z++;
	  if($z==7)
	  {
	  	print "</tr>";
	  	$z=0;
		}
	}
	if($z!=7 and $z!=0)
	{
	  for($i=0; $i<(7-$z); $i++)
	  {print "<td bgcolor=\"#CCC\"></td>";}
	  print "</tr>";
	}
	?>
</table>

<script>
	$(document).ready(function() {
		$('#print-cal-button').click(function() {
			window.open("../calendar/print-cal-staff.php?year="+"<?php print $year; ?>"+"&month="+"<?php print $month; ?>",'_blank');
		});

		//LOAD CALENDAR DATA
		
		$('#cal-month').change(function() {
			$('#working').fadeIn('fast');
			month = $('#cal-month').val();
			$.ajax({
				type: "POST",
				url: "../calendar/cal-process-date.php",
				data : {month : month},
				success: function()
				{
					$("#cal-window").load('cal-full.php', function(){
						$('#cal-window').fadeIn('fast');
						$('#working').fadeOut('fast');
					});
				}
			});
		});

		$('#cal-year').change(function() {
			$('#working').fadeIn('fast');
			year = $('#cal-year').val();
			$.ajax({
				type: "POST",
				url: "../calendar/cal-process-date.php",
				data : {year : year},
				success: function()
				{
					$("#cal-window").load('cal-full.php', function(){
						$('#cal-window').fadeIn('fast');
						$('#working').fadeOut('fast');
					});
				}
			});
		});

	}); 
</script>