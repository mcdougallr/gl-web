<!DOCTYPE html> 
<head> 
<link href="reportprint.css" rel="stylesheet" type="text/css" /> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>CFTC Report</title> 
 
<style> 
	td {vertical-align: top;} 
	p {margin-top: 3px;margin-bottom: 3px;}
</style> 
 
<?php 
	session_start(); 
 
	include ('../../shared/dbconnect.php'); 
	include ('../../shared/clean.php'); 
	include ('../../shared/authenticate.php'); 
	 
	if (isset($_GET['s'])){$session = cleantext($_GET['s']);} 
	else {$session = "";} 
	 
?> 
</head> 
<body> 
	<?php  
			 
	//Retrieve Student List	 
 	 
	$stmt = $conn->prepare("SELECT * FROM ss_registrations 
													LEFT JOIN ss_sessions ON ss_sessions.session_id = ss_registrations.accepted_session 
													LEFT JOIN ss_programs ON ss_sessions.session_program_code = ss_programs.program_code 
													WHERE accepted_session = :accepted_session 
													ORDER BY student_name_last, student_name_first"); 
	$stmt->bindValue(':accepted_session', $session); 
	$stmt->execute();						 
	$student_results = $stmt->fetchAll(); 
 
 	$studentcount = 0;
	$first = 1;
	foreach ($student_results as $student) 
	{ 
		if ($studentcount == 0) 
		{
			if ($first == 1){print "<div class=\"pagebreak\">";$first = 0;}
			else {print "</div><div class=\"pagebreak\">";}
		}
		?>
		<div style="text-align: center;"> 
			<img src="lethead.png" width=100%/> 
		</div> 
		
	  	<table id="cftc" align="center" width="100%"> 
		  	<tr>			  		 
                <td colspan=3> 
                    <table style="background: #CCC; font-size: 1.5em; margin: 5px auto; width:100%;"> 
                        <tr> 
                            <td > 
                                <div style="font-weight: bold; font-size: 1.5em;font-variant: small-caps">Official Tax Receipt</div> 
                                <div style="font-weight: bold; font-size: 1.1em;">Limestone District School Board</div> 
                                <div>220 Portsmouth Avenue, Kingston, Ontario, Canada  K7M 0G2</div> 
                            </td>        
                        </tr> 
                  	</table> 
		  		</td> 
		  	</tr>
		  	<tr> 
		  		<td class="left" width="40%"><span style="font-weight: bold">Date of Payment:</span> June 1, 2016</td> 
		  		<td class="left" width="40%"><span style="font-weight: bold">Activity:</span> Gould Lake Summer Program (<?php print $student['program_code']; ?>)</td> 
		  		<td rowspan=3 align="center" width="20%"><img src="../images/ldsb.jpg" width="60" height="90" alt="ldsb.jpg"> </td>
		  	<tr> 
            	<td class="left"><span style="font-weight: bold">Student Name:</span> <?php print $student['student_name_first']." ".$student['student_name_last']; ?></td> 
                <td class="left"><span style="font-weight: bold">Amount Received:</span> $<?php print $student['admin_paid_amount']; ?></td>                    
		  	</tr>				  	 
		  	<tr>			  		 
		  		<td class="left"><span style="font-weight: bold">Payee:</span> <?php print $student['g1_name_first']." ".$student['g1_name_last']; ?></td> 
		  		<td class="left"><span style="font-weight: bold">Authorized Signature</span><br /><br /><br /></td> 
		  	</tr>				  				  	 
	 	</table> 
		<?php 
		$studentcount++;
		if ($studentcount == 3){$studentcount = 0;}
		else {print "<br /><br /><br /><br />";}
	}  
	 ?>	 	 
</body> 
</html> 
