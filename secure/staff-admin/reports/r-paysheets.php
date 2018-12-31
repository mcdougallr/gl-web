<?php
session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');

?>
<!doctype html>
<html lang="en">
<head>
<link rel="stylesheet" type="text/css" href="reportprint.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Gould Lake Staff Paysheets</title>
<style type="text/css">
<!--
html {background: none;}
-->

</style>
</head>
<body>

<?php

$count = count($_POST['staff_id']);

for ($i = 0; $i < $count; $i++) 
{
	if (isset($_POST['printchecked'][$i])) {$print = cleantext($_POST['printchecked'][$i]);}
	else $print = 0;
	
	if ($print == 1)
	{
		$staff_id = cleantext($_POST['staff_id'][$i]);
		
		$stmt = $conn->prepare("SELECT * FROM staff 
														WHERE staff_id = :staff_id");
		$stmt->bindParam(':staff_id', $staff_id);
		$stmt->execute();
		
		$staff = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$totalpay=0; ?>
		<div class="pagebreak"> 
		  <h1>Gould Lake Payroll - Summer 2017</h1>
		  <h4 style="text-align: left;"><?php print $staff['staff_name_last'].", ".$staff['staff_name_first']; ?>
		  </h4>
				<table class="pure-table pure-table-bordered gl-staff-payroll">
	          		<tr>
			            <th>Pay Period Start</th>
			            <th>Pay Period End</th>
			            <th>Work Dates</th>
			            <th>Pay Rate</th>
			            <th>Days Worked</th>
			            <th>Pay</th>
			            <th>Pay Date</th>
			            <th>Batch #</th>
		          	</tr>
	          		<?php 
		            	$totalpay = 0;
		            	$totalpaydays = 0;

		             $periodpay = 0;
		             $periodpaydays = 0;
						
						$paydateold = "";
						$paydatenew = "";
						$paydatefirst = 1;
		
				         	//Get Pay Periods
				           	$payperiodquery = "SELECT * FROM staff_ss_payperiods ORDER BY period_start"; 
		              
				              //For Each Pay Period - Get Workdays
				              foreach ($conn->query($payperiodquery) as $payperiod)
				              {
				              		$paydatenew = $payperiod['period_paydate'];	
									if ($paydatefirst == 1) {$paydatefirst = 0;}
									elseif ($paydatenew != $paydateold) 
									{
										// Period Totals
										?>
										<tr class="periodtotal">
					              			<td class="periodtotal" colspan=4>Total for Pay Period</td>
					              			<td><?php print $periodpaydays; ?></td>
					             			<td>$<?php print number_format($periodpay, 2) ; ?></td>
					             			<td><?php print date("M d", strtotime($paydateold)); ?></td>
					             			<td></td>
					             		</tr>
										<?php
										$periodpay = 0; 
										$periodpaydays = 0;
									}
										
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
																WHERE event_date = :event_date AND workday_staff_id = :staff_id AND event_type != 'V' AND event_type != 'T'");
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
											if ($workdaytotal < 1){$workdaydays = $workdaydays."*";}		
										}
										$weekpaydays = $weekpaydays + $workdaytotal;		

										//Increment Date
										$workdate = date("Y-m-d", strtotime("+1 day", strtotime($workdate)));
									}
									$weekpay = $weekpaydays * $staff['admin_rate_of_pay'];
				                	?>
		                				
		            				<tr>
		            					<td width="15%"><?php print date("M d", strtotime($payperiod['period_start'])); ?></td>
		            					<td width="15%"><?php print date("M d", strtotime($payperiod['period_end'])); ?></td>
		            					<td width="20%"><?php print $workdaydays; ?></td>
		            					<td width="10%"><?php print $staff['admin_rate_of_pay'] ?></td>
		            					<td width="10%"><?php print $weekpaydays; ?></td>
		            					<td width="10%">$<?php print number_format($weekpay, 2); ?></td>
		            					<td width="10%" style="background: #222;"></td>
		            					<td width="10%" style="background: #222;"></td>
									</tr>
									
									<?php
				                	$periodpay = $periodpay + $weekpay;
				                $periodpaydays = $periodpaydays + $weekpaydays;
				                
				                $totalpay = $totalpay + $weekpay;
				                $totalpaydays = $totalpaydays + $weekpaydays;
						
					                //Calculate Deductions
					                $stmt = $conn -> prepare("SELECT * FROM staff_paydeductions
																				WHERE deduction_date = :period_end AND deduction_staff_id = :staff_id");
				                $stmt -> bindValue(':period_end', $payperiod['period_end']);
				                $stmt -> bindValue(':staff_id', $staff_id);
				                $stmt -> execute();
				                $paydeductions = $stmt -> fetchAll();
				                foreach ($paydeductions as $paydeduction)
					                {
						               	if (isset($paydeduction))
										{?>
		               						<tr>
												<td colspan=5 style="text-align: right;"><em>Staff Training Course Deduction&nbsp;</em></td>
		                						<td><em>-$<?php print number_format($paydeduction['deduction_amount'], 2) ; ?></em></td>
		                						<td style="background: #222;"></td>
		                						<td style="background: #222;"></td>
											</tr>
											<?php
		                					$totalpay = $totalpay - $paydeduction['deduction_amount'];
					                   $periodpay = $periodpay - $paydeduction['deduction_amount'];
										}
		            				}
									$paydateold = $paydatenew;
								}?>

					<tr class="summertotal">
						<td class="summertotal" colspan=5>Summer Total</td>
		           		<td><?php print $totalpaydays; ?></td>
		            	<td>$<?php print number_format($totalpay, 2); ?></td>
		            	<td style="background: #000;"></td>
	            	</tr>
	     		</table>
		</div>
	<?php
	}
} ?>
</body>
</html>
