<?php
session_start();
$db = "outed";
?>

<!DOCTYPE html>
<html>
  	<head>
  		<title>Gould Lake Registration System</title>
  	
  		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
   
		<link rel="stylesheet" href="../../scripts/pure6/pure-min.css">
		<link rel="stylesheet" href="../../scripts/pure6/grids-responsive-min.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
    	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>      
    	<link href="regcss.css" rel="stylesheet" type="text/css" />
    	
    	 <script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
  	</head>
  	
<?php
include ('../shared/dbconnect.php');
include ('../shared/functions.php');
include ('../shared/jsfunctions.php');

//GATHER PREFERENCES
$stmt = $conn->prepare("SELECT * FROM gl_prefs");
$stmt->execute();
$prefs = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['rid'])) {$rid = cleantext($_GET['rid']);}
if (isset($_GET['rcd'])) {$rcd = cleantext($_GET['rcd']);}

if (isset($rid))
{
	$stmt = $conn->prepare("UPDATE gl_registrations
													SET status_accept_read=1
													WHERE registration_id = :registration_id");
	$stmt->bindValue(':registration_id', $rid);
	$stmt->execute();
}

//SAVE UPDATE
if (isset($_POST['submit_accept']) and  $_POST['submit_accept'] == "Yes")
{
	$stmt = $conn->prepare("UPDATE gl_registrations
													SET status_accept_confirmed=1
													WHERE registration_id = :registration_id");
	$stmt->bindValue(':registration_id', $rid);
	$stmt->execute();
}
?>

<body>
	<div id="reg-header">
	  	<div id=header-top class="pure-g">
		    <div id="gl-title" class="pure-u-1 pure-u-md-2-3"> 
				<div id="mobile-menu-button"><i class="fa fa-bars"></i></div>
				<img id="gl-sunman" src="/index_files/header sunny.png" alt="sunman.png">
				<span>Gould Lake Outdoor Centre</span>            
			</div>
		    <div id="gl-subtitle" class="pure-u-1 pure-u-md-1-3">
		      <span>Registration 2018</span> 
		    </div>
		</div>
	</div>
	<div id="gl-main" style="top: 66px;">
		<div id="gl-wrapper">
			<h1 style="font-family: FTYS,Georgia,Verdana,serif;font-weight: normal;margin: 10px 0;text-align: center;">Session Acceptance</h1>
			<p>Your registration has been accepted for the session below.  
			 Please click on the ACCEPT button below to confirm that you have received this information.
			 If you have any questions, please contact the Gould Lake office (613-376-1433) or
			 send us an email (outed@limestone.on.ca).</p>
	
			<hr />
	
			<form id="accept-form" style="padding: 0 20px;" class="pure-form pure-form-stacked" method="post" action="">
				<input type=hidden name="registration_id" value = "<?php print $rid; ?>" />
				<input type=hidden name="submit_accept" value = "Yes" />
				<?php 
				$stmt = $conn->prepare("SELECT * FROM gl_registrations 
																LEFT JOIN gl_programs ON gl_registrations.selected_program = gl_programs.program_id 
																LEFT JOIN gl_sessions ON gl_registrations.accepted_session = gl_sessions.session_id
																WHERE registration_id = :registration_id AND registration_code = :registration_code");
				$stmt->bindValue(':registration_id', $rid);
				$stmt->bindValue(':registration_code', $rcd);
				$stmt->execute();
				$student = $stmt->fetch(PDO::FETCH_ASSOC);
				?>
				<h2 style="font-family: FTYS,Georgia,Verdana,serif;font-weight: normal;margin: 5px 0;">Registration Information</h2>
	
				<h4 style="text-align: left;">Student Name: <em><?php print $student['student_name_first']. " ". $student['student_name_last']; ?></em></h4>
				<h4 style="text-align: left;">Accepted Session: <em><?php print $student['session_program_code']. " ". $student['session_number']; ?></em></h4>
				<h4 style="text-align: left;">Session Dates: <em><?php print date("M. d",strtotime($student['session_start']))." - ".date("M. d, Y",strtotime($student['session_end'])); ?></em></h4>
			</form>
			<?php 
			if ($student['session_comment']!="")
			{print "<h4 style=\"text-align: left;\">Session Notes: <em>".$student['session_comment']."</em></h4>";}
	
			print "<hr />";
	
			if ($student['status_accept_confirmed']==1)
			{
				print "";
				print "<h2 style=\"font-family: FTYS,Georgia,Verdana,serif;font-weight: normal;margin: 5px 0;text-align: center;\">Thank you for confirming your session.</h2><br>";
			}
			else
			{
			print "<div style=\"text-align: center;\"><button class=\"gl-submit-button\" style=\"font-size: 1.2em;margin: 0 auto;padding: 3px 25px 5px 21px;border: 1px #DDD solid;border-radius: 10px;box-shadow: 1px 1px #777;\"><i class=\"fa fa-check\"></i> Accept</button></div>";
			}
			
			include('footer.php'); ?>
		</div>
	</div>
</body>
</html>
<script>
	$(document).ready(function(){
	 	$(".gl-submit-button").click(function(){
	 		$("#accept-form").submit();
	 	});
	 });
</script>