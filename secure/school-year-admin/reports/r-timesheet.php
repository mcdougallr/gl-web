<!DOCTYPE html>
<head>
	<link href="reportprint.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../../../scripts/pure/pure-min.css">  
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>School Year Timesheets</title>
	
	<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');
	
	$dateinput = $_SESSION['date'];
	
	if (date('N', strtotime($dateinput)) == 7) {
		$start_day = $dateinput;
	}
	else {
		$start = strtotime('last sunday, 12pm', strtotime($dateinput));
		$start_day = date('Y-m-d', $start);
	}
		if (date('N', strtotime($dateinput)) == 6) {
		$end_day = $dateinput;
	}
	else {
		$end = strtotime('next saturday, 11:59am', strtotime($dateinput));
		$end_day = date('Y-m-d', $end);
	}
	
	$stmt = $conn->prepare("SELECT * FROM staff
							WHERE staff_access > 1 AND admin_rate_of_pay != 0
							ORDER BY staff_name_last, staff_name_first");
	$stmt->execute();						
	$staff_results = $stmt->fetchAll();
	?>
	
<style>
	body {
		font-family: Times;
	}
	h1 {
		font-family: Times;
		font-size: 1.2em;
		font-weight: normal;
		margin: 5px;
	}
	h2 {
		font-family: Times;
		font-size: 1em;
		font-weight: normal;
		margin: 5px;
	}
	th, td {
		font-family: Times;
		font-size: 1em;	
	}
</style>

</head>
<body>
	<?php
	foreach ($staff_results as $staff)
	{
		$stmt = $conn -> prepare("SELECT SUM(workday_percentage) AS workday_sum FROM staff_workdays
									LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
									WHERE event_date BETWEEN :event_date_start AND :event_date_end AND workday_staff_id = :staff_id");
        $stmt -> bindValue(':event_date_start', $start_day);
        $stmt -> bindValue(':event_date_end', $end_day);
        $stmt -> bindValue(':staff_id', $staff['staff_id']);
        $stmt -> execute();
        $workdays = $stmt -> fetch(PDO::FETCH_ASSOC);
        $workday_check = $workdays['workday_sum'];
		
		if ($workday_check > 0) 
		{
			?>
			<div class="pagebreak">
				<div class="pure-g">
					<div class="pure-u-1-6">
	           <img src="ldsb.jpg" width="67" height="100" alt="ldsb.jpg">
					</div>
					<div class="pure-u-2-3">
						<h1>LIMESTONE DISTRICT SCHOOL BOARD</h1>
						<h1>CASUAL ACCOUNT FORM</h1>
						<h2>Weekly Timesheet</h2>
						<h2>Paper Colour: <span style="font-weight: bold;">WHITE</span></h2>
					</div>
					<div class="pure-u-1">
						<h2 style="text-align: left;">One Week Pay Period: 
							From <span style="font-weight: bold;font-size: 1.1em;margin: 0 10px;"><?php print $start_day; ?></span> 
							To <span style="font-weight: bold;font-size: 1.1em;margin: 0 10px;"><?php print $end_day; ?></span>
						</h2>
					</div>
					<div class="pure-u-3-4">
						<h2 style="text-align: left;">Full Name: <span style="font-weight: bold;font-size: 1.1em;margin: 0 10px;"><?php print $staff['staff_name_first']." ".$staff['staff_name_last']; ?></span></h2>
					</div>
					<div class="pure-u-1-4">
						<h2 style="text-align: left;">ID #: <span style="font-weight: bold;font-size: 1.1em;margin: 0 10px;"><?php print $staff['admin_LDSBnum']; ?></span></h2>
					</div>
					<div class="pure-u-1">
						<h2 style="text-align: left;">Location: <span style="font-weight: bold;font-size: 1.1em;margin: 0 10px;">Gould Lake Outdoor Centre</span></h2>
					</div>
					<div class="pure-u-1">
						<br />
					</div>
					<div class="pure-u-1">
						<table width=100%>
							<tr>
								<th></th>
								<th>Date</th>
								<th>From</th>
								<th>To</th>
								<th>From</th>
								<th>To</th>
								<th>Days</th>
								<th>Name of Regular<br />Employee Replaced</th>
								<th>Type of Work</th>
							</tr>
							<?php
								$date = $start_day;
								$pay_week = 0;
								
								while (strtotime($date) <= strtotime($end_day))		
								{
									$pay_day = 0;
									$stmt = $conn -> prepare("SELECT SUM(workday_percentage) AS workday_sum FROM staff_workdays
																LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
																WHERE event_date = :event_date AND workday_staff_id = :staff_id");
							        $stmt -> bindValue(':event_date', $date);
							        $stmt -> bindValue(':staff_id', $staff['staff_id']);
							        $stmt -> execute();
							        $workday = $stmt -> fetch(PDO::FETCH_ASSOC);
																		
									if ($workday['workday_sum'] > 0) {$pay_day = 1;}
									else $pay_day = $workday['workday_sum'];

									$pay_week = $pay_week + $pay_day; 
									?>					
									<tr>
										<td><?php print date('D', strtotime($date)); ?></td>
										<td><?php print date('M d', strtotime($date)); ?></td>
										<td></td><td></td><td></td><td></td><td><?php print number_format($pay_day, 1, '.', ''); ?></td><td></td><td></td>
									</tr>
								<?php
								$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
								}
								?>
								<tr>
									<td colspan=6><h2 style="text-align: right;font-weight: bold;">TOTAL DAYS</h2></td>
									<td><?php print number_format($pay_week, 1, '.', ''); ?></td>
									<td colspan=2></td>
								</tr>
						</table>
					</div>			
					<div class="pure-u-7-12">
						<br />
					</div>
					<div class="pure-u-5-12">
						<div style="padding: 30px 20px 0 20px;">
							<h2 style="text-align: left;">__________________________________</h2>
							<h2 style="text-align: left;">Employee Signature</h2>
							</div>
					</div>
					<div class="pure-u-7-12">
						<div style="padding: 30px 20px 0 20px;">
							<h2 style="text-align: left;">_________________________________________</h2>
							<h2 style="text-align: left;">Certified Correct: Supervisor</h2>
						</div>
					</div>
					<div class="pure-u-5-12">
						<div style="padding: 30px 20px 0 20px;">						
							<h2 style="text-align: left;">__________________________________</h2>
							<h2 style="text-align: left;">Approved: Manger/Principal</h2>
						</div>
					</div>
					<div class="pure-u-7-12">
						<div style="padding: 30px 20px 0 20px;">
							<h2 style="text-align: left;">Code _________ Units _________ Rate _
								<span style="text-decoration: underline;font-size: 1.1em;font-weight: bold;">
								<?php 
									if ($staff['admin_fws_payrate'] == 0) {print "114.24";}
									else {print $staff['admin_fws_payrate'];}
								?>
								</span>_</h2>
						</div>
					</div>
					<div class="pure-u-5-12">
					</div>
					<div class="pure-u-7-12">
						<div style="padding: 0px 20px 0 20px;">
							<h2 style="text-align: left;">Code _________ Units _________ Rate _________</h2>
						</div>
					</div>
					<div class="pure-u-5-12">
					</div>
					<div class="pure-u-7-12">
						<div style="padding: 0px 20px 0 20px;">
							<h2 style="text-align: left;">Rate$ ____________________________________</h2>
						</div>
					</div>
					<div class="pure-u-5-12">
						<div style="padding: 0px 20px 0 20px;">						
							<h2 style="text-align: left;text-decoration: underline;font-size: 1.1em;font-weight: bold;">0455-10-600-192-6-613</h2>
							<h2 style="text-align: left;">Budget Account Code</h2>
						</div>
					</div>
					<div class="pure-u-7-12">
						<div style="padding: 0px 20px 0 20px;">						 
							<h2 style="text-align: left;font-size: .7em;">Plus 4% Vac 
								<i style="font-size: 1.4em;" class="fa fa-square-o"></i> Plus 3.5% Stat Holiday 
								<i style="font-size: 1.4em;" class="fa fa-square-o"></i> Plus 9% in Lieu of Benefits 
								<i style="font-size: 1.4em;" class="fa fa-square-o"></i>
							</h2>
						</div>
					</div>
					<div class="pure-u-7-12">
					</div>
					<div class="pure-u-5-12">
						<div style="padding: 30px 20px 0 20px;">						
							<h2 style="text-align: left;">__________________________________</h2>
							<h2 style="text-align: left;">Pay Date</h2>
						</div>
					</div>
					<div class="pure-u-5-12">
						<div style="padding: 10px 20px 0 20px;">						 
							<h2 style="text-align: left;font-size: .7em;">Revised January 2010</h2>
						</div>
					</div>
				</div>
			</div>
	<?php
		}	
	}
	?>

</body>
</html>
