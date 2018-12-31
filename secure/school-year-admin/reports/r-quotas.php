<!DOCTYPE html>
<head>
<link href="reportprint.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<?php
	session_start();
	
	include ('../../shared/dbconnect.php');
	include ('../../shared/functions.php');
	include ('../../shared/authenticate.php');

	$stmt = $conn->prepare("SELECT * FROM sy_season WHERE season_code = \"F\"");
	$stmt->execute();
	$fall_results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn->prepare("SELECT * FROM sy_season WHERE season_code = \"W\"");
	$stmt->execute();
	$winter_results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn->prepare("SELECT * FROM sy_season WHERE season_code = \"S\"");
	$stmt->execute();
	$spring_results = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$stmt = $conn->prepare("SELECT SUM(population) FROM sy_schools WHERE school_div = \"E\"");
	$stmt->execute();
	$elempoparr = $stmt->fetch(PDO::FETCH_ASSOC);

	$elempop = $elempoparr['SUM(population)'];
	
	$totalclasses = $fall_results['season_classes']+$winter_results['season_classes']+$spring_results['season_classes'];
	
	$totalcompquota = 0;
	$totaltotalquota = 0;
	$totalfallclasses = 0;
	$totalwinterclasses = 0;
	$totalspringclasses = 0;
	
?>
</head>
<body>
	<div style="text-align: center;">
		<h2 style="display: inline;">GL School Year Quotas (<?php print date("M d, Y"); ?>)</h2>
		<img src="sunman-black.png" width="30"/>
	</div>
    <br />
    <table width="100%" align="center">
    	<tr>
        	<th rowspan=2 style="text-align: left;" width="20%">School</th>
            <th rowspan=2 width="7%">Pop</th>
            <th colspan=2><?php print $totalclasses; ?></th>
            <th><?php print $fall_results['season_classes']; ?></th>
            <th><?php print $winter_results['season_classes']; ?></th>
            <th><?php print $spring_results['season_classes']; ?></th>
            <th rowspan=2 width="38%">Notes</th>
		</tr>
        <tr>
        	<th width="7%">C</th>
            <th width="7%">T</th>
            <th width="7%">F</th>
            <th width="7%">W</th>
            <th width="7%">S</th>
        </tr>     
	<?php 
    $schoolquery = "SELECT * FROM sy_schools WHERE population != 0 ORDER BY school_div, school_name";
    foreach ($conn->query($schoolquery) as $school)
    {
		 $compquota = ROUND($school['population']/$elempop*$totalclasses);
		 $totalcompquota = $totalcompquota + $compquota;
		 
		 $totalquota = $school['F_quota']+$school['W_quota']+$school['S_quota'];
		 $totaltotalquota = $totaltotalquota + $totalquota;
		 
		 $totalfallclasses = $totalfallclasses + $school['F_quota'];
		 $totalwinterclasses = $totalwinterclasses + $school['W_quota'];
		 $totalspringclasses = $totalspringclasses + $school['S_quota'];
		 
		 print "<tr><td style=\"text-align: left;\">".$school['school_name']."</td>";
		 print "<td class=\"center\">".$school['population']."</td>";
		 print "<td class=\"center\">".$compquota."</td>";
		 print "<td class=\"center\">".$totalquota."</td>";
		 print "<td class=\"center\">".$school['F_quota']."</td>";
		 print "<td class=\"center\">".$school['W_quota']."</td>";
		 print "<td class=\"center\">".$school['S_quota']."</td>";
		 print "<td style=\"text-align: left;\">".$school['school_notes']."</td></tr>";
	} ?>
    <tr>
    	<th>Totals</th>
        <th><?php print $elempop; ?></th>
        <th><?php print $totalcompquota; ?></th>
        <th><?php print $totaltotalquota; ?></th>
        <th><?php print $totalfallclasses; ?></th>
        <th><?php print $totalwinterclasses; ?></th>
        <th><?php print $totalspringclasses; ?></th>
        <th></th>
 	</table>
</body>
</html>
