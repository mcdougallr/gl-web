<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Food Schedule</title>
</head>
<body>
	<?php
	session_start();
	$year = date("Y");
	$start_date = $year."-01-01";
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	include ('../../shared/eventfunctions.php');

	$foodquery = "SELECT * FROM schedule_events
								LEFT JOIN schedule_food ON schedule_food.food_id = schedule_events.event_type_id
								WHERE schedule_events.event_date > '$start_date' AND schedule_events.event_type = 'F'
								ORDER BY event_date";
	
	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Food Schedule</h1>
	<table width="100%" class="pure-table pure-table-bordered">
		<tr>
			<th>Date</th>
			<th>Title</th>
			<th>Staff</th>
			<th>Notes</th>
		</tr>
			
		<?php
		foreach ($conn->query($foodquery)  as $food)
	    { 
			$new_date = $food['event_date'];
	    	if ($new_date != $old_date)
			{
	    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
			}?>
			<tr>
				<td><?php print $food['event_date']; ?></td>
				<td><?php print $food['food_title']; ?></td>
				<td>
				<?php
				$count = 0;
				$stafflist = getstafflist($food['event_id'],$conn);
				
				foreach ($stafflist as $staff)
				{
					if ($count != 0) {print ", ";}
					print $staff['staff_name_common']." ".$staff['staff_name_last'];
					$count++;
				}
				?>
				</td>
				<td style="white-space: pre-wrap;"><?php print $food['food_notes']; ?></td>
			</tr>
		<?php					
		}
	?>
	</table>
</body>
</html>
