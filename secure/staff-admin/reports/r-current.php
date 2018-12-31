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
		<h1>Current Gould Lake Staff<img style="width: 35px;margin-left: 5px;vertical-align:-3px;" src="../../shared/sunman-black.png"/></h1>
		<table align=center style="min-width: 300px;">
			<tr> 
				<th style="text-align: center">Staff</th>
			</tr>
			<?php
				$odd = 1;
            	$staffquery = "SELECT * FROM staff 
								WHERE admin_archive = 'No'
								ORDER BY staff_name_last, staff_name_common";
				foreach ($conn->query($staffquery) as $staff)
				{?>
                	<tr <?php if ($odd == 1) {print "style=\"background: #DDD;\"";$odd = 0;} else {$odd = 1;} ?>>
                    	<td style="text-align: center;"><?php print $staff['staff_name_last'].", ".$staff['staff_name_first'];?></td>
					</tr>
                <?php } ?>
		</table>
	</body>
</html>