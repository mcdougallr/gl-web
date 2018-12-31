<!DOCTYPE html>
<head>
<link href="_reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
<title>Supervision Schedule</title>
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

	$supervisionquery = "SELECT * FROM schedule_events
											LEFT JOIN schedule_supervision ON schedule_supervision.supervision_id = schedule_events.event_type_id
											WHERE schedule_events.event_date > '$start_date' AND schedule_events.event_type = 'S'
											ORDER BY event_date";
		
	$bgcolor = "#FFF";
	$new_date = "";
	$old_date = "";
	?>
	<h1><img src="../../shared/sunman-black.png" width="30" style="vertical-align: -3px;margin-right: 5px;"/>Supervision Schedule</h1>
	<table width="100%" class="pure-table pure-table-bordered">
		<tr>
			<th>Date</th>
			<th>Title</th>
			<th>Staff</th>
			<th>Notes</th>
		</tr>
			
		<?php
		foreach ($conn->query($supervisionquery)  as $supervision)
	    { 
	    	$new_date = $supervision['event_date'];
	    	if ($new_date != $old_date){
	    		if ($bgcolor == "#F2F2F2"){$bgcolor = "#FFF";}
				else {$bgcolor = "#F2F2F2";}
	    	}?>
			<tr style="background: <?php print $bgcolor; ?>">
				<td><?php print $supervision['event_date']; ?></td>
				<td><?php print $supervision['supervision_title']; ?></td>
				<td>
				<?php
				$count = 0;
				$stafflist = getstafflist($supervision['event_id'],$conn);
				
				foreach ($stafflist as $staff)
				{
					if ($count != 0) {print ", ";}	
					print $staff['staff_name_common']." ".$staff['staff_name_last'];
					$count++;
				}
				?>
				</td>
				<td style="white-space: pre-wrap;"><?php print $supervision['supervision_notes']; ?></td>
			</tr>
			<?php		
			$old_date = $new_date;			
		}
	?>
	</table>
</body>
</html>
