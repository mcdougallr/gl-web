<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');

?>

<html>
	<head>
		<link href="reportprint.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Pay Total Report</title>
	</head>
	
	<body>
		<h1>Payroll Overview<img style="width: 35px;margin-left: 5px;vertical-align:-3px;" src="../../shared/sunman-black.png"/></h1>
		<table align=center>
			<tr> 
				<th align=left width="30%">Staff Name</th>
				<th width="20%">Rate of Pay</th>
				<th width="25%">Days Working</th>
				<th width="25%">Total Pay</th>
				<th width="25%">Deductions</th>
			</tr>
			<?php
			$count = 0;
			$grandtotal = 0;
			$grandtotaldeductions = 0;
			$totalstaffworkdays = 0;
			$findstaffquery = "SELECT * FROM staff 
								WHERE admin_archive = 'No'
													ORDER BY staff_name_last, staff_name_common";
			foreach ($conn->query($findstaffquery) as $staff)
			{ ?>
				<tr <?php if ($count != 0) {print "style=\"background: #DDD\"";$count = 0;} else {$count = 1;} ?>>
					<td align=left><?php print $staff['staff_name_last'].", ".$staff['staff_name_first']; ?></td>
					
					<?php
		          		$i = 0;
		            	$totalpay = 0;
		            	$totalpaydays = 0;
						$totaldeductions = 0;
		
			         	//Get Pay Periods
			           	$payperiodquery = "SELECT * FROM staff_ss_payperiods ORDER BY period_start"; 
		              
			              //For Each Pay Period - Get Workdays
			              foreach ($conn->query($payperiodquery) as $payperiod)
			              {
			              		$workdate = $payperiod['period_start'];
								$end_day = $payperiod['period_end'];
								$workdaydays = "";
								$weekpaydays = 0;
								$weekpay = 0;
								while (strtotime($workdate) <= strtotime($end_day))		
								{
									$workdaytotal = 0;
				              		//Workdays
										$stmt = $conn -> prepare("SELECT SUM(workday_percentage) AS workday_sum FROM staff_workdays
																LEFT JOIN schedule_events ON schedule_events.event_id = staff_workdays.workday_event_id
																WHERE event_date = :event_date AND workday_staff_id = :staff_id");
										$stmt -> bindValue(':event_date', $workdate);
										$stmt -> bindValue(':staff_id', $staff['staff_id']);
										$stmt -> execute();
										$workday = $stmt -> fetch(PDO::FETCH_ASSOC);

										// Combine and Max Out Percentages
										$workdaytotal = $workday['workday_sum'];
										if ($workdaytotal > 1) {$workdaytotal = 1;}
										if ($workdaytotal > 0) {
											if ($workdaydays == "") {$workdaydays = date("M d", strtotime($workdate));}
											else {$workdaydays = $workdaydays.", ".date("M d", strtotime($workdate));}
										}
										$weekpaydays = $weekpaydays + $workdaytotal;		

										//Increment Date
										$workdate = date("Y-m-d", strtotime("+1 day", strtotime($workdate)));
								}
								$weekpay = $weekpaydays * $staff['admin_rate_of_pay'];
			                
			                $totalpay = $totalpay + $weekpay;
			                $totalpaydays = $totalpaydays + $weekpaydays;
					
				                //Calculate Deductions
				                $stmt = $conn -> prepare("SELECT * FROM staff_paydeductions
																			WHERE deduction_date = :period_end AND deduction_staff_id = :staff_id");
			                $stmt -> bindValue(':period_end', $payperiod['period_end']);
			                $stmt -> bindValue(':staff_id', $staff['staff_id']);
			                $stmt -> execute();
			                $paydeductions = $stmt -> fetchAll();
			                foreach ($paydeductions as $paydeduction)
				                {
	                				$totaldeductions = $totaldeductions + $paydeduction['deduction_amount'];
	            				}
							}
						?>
						<td align=center>$<?php print $staff['admin_rate_of_pay']; ?></td>	
						<td align=center><?php print $totalpaydays; ?></td>
						<td align=right>$<?php print number_format($totalpay, 2, '.', ''); ?></td>
						<td align=right>$<?php print number_format($totaldeductions, 2, '.', ''); ?></td>
					</tr>
				<?php 
				$totalstaffworkdays = $totalstaffworkdays + $totalpaydays;
		      $grandtotal = $grandtotal + $totalpay;
		      	$grandtotaldeductions = $grandtotaldeductions + $totaldeductions;
			
			} ?>
		
			<tr>
				<th colspan=2 style="text-align: right;">Total:</th>
				<th><?php print $totalstaffworkdays; ?></th>
                <th>$<?php print number_format($grandtotal, 2, '.', '') ?></th>
                <td align=right>$<?php print number_format($grandtotaldeductions, 2, '.', ''); ?></td>
			</tr>
		</table>
	</body>
</html>