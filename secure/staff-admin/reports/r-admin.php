<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');	
	include ('../../shared/authenticate.php');
?>

<html>
	<head>
		<link href="reportprint.css" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<title>Admin Report</title>
	</head>
	<body>
		<h1>Staff Admin Info<img style="width: 35px;margin-left: 5px;vertical-align:-3px;" src="../../shared/sunman-black.png"/></h1>
		<table align=center>
			<tr> 
				<th style="text-align: left">Staff</th>
				<th>LDSB #</th>
				<th>CPIC on File</th>
				<th>Policy Complete</th>
				<th>OSHA</th>
				<th>WA OFFDEC</th>
				<th>Payroll Form?</th>
				<th>Bank Info?</th>
				<th>Tax Forms?</th>
			</tr>
			<?php
				$odd = 1;
            	$staffquery = "SELECT * FROM staff 
								WHERE admin_archive = 'No'
								ORDER BY staff_name_last, staff_name_common";
				foreach ($conn->query($staffquery) as $staff)
				{?>
                	<tr <?php if ($odd == 1) {print "style=\"background: #DDD;\"";$odd = 0;} else {$odd = 1;} ?>>
                    	<td style="text-align: left"><?php print $staff['staff_name_last'].", ".$staff['staff_name_first'];?></td>
                		<td><?php print $staff['admin_LDSBnum']; ?></td>
                		<td><?php if ($staff['admin_CPIC'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
                		<td><?php if ($staff['admin_policy_chat'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
                        <td><?php if ($staff['admin_OSHA'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
                        <td><?php if ($staff['admin_contract'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
                		<td><?php if ($staff['admin_payroll_form'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
                		<td><?php if ($staff['admin_deposit'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
                		<td><?php if ($staff['admin_tax'] == 1){print "<i class=\"fa fa-check\"></i>";} ?></td>
					</tr>
                <?php } ?>
		</table>
	</body>
</html>