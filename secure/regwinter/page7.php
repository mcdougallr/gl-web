<?php 	
	$page = 9;
	include ('header.php');
		
	if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from ss_registrations
								LEFT JOIN ss_programs ON ss_registrations.selected_program = ss_programs.program_id
								WHERE registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	}

if ($_SESSION['registration_id'] == 0){header("Location: http://outed.limestone.on.ca/regfiles/");}	 
else {
?>
	<div id="gl-main">
		<?php include("menu.php"); ?>
		<div id="gl-wrapper">	   
	      	<div class="pure-g">	      	
				<div class="pure-u-1">	    
					<p><?php print $data['student_name_common']; ?>'s registration has been submitted.</p>
					<p>An email has been sent to you at <strong><?php print $data['contact_email']; ?></strong></p>
					<p>Please save this email as it contains  the details of your registration and a link to the registration 
						package should you require an additional copy.</p>
					<p><strong>In order to complete your registration, please print out the registration package, ensure the information is correct, sign the necessary documents and attach payment. </strong>Please mail the completed package to:</p>
					<p><em></em>Gould Lake Outdoor Centre
					<br>2857 Rutledge Rd.
				    <br>Sydenham, ON
				     <br>K0H 2T0</em></p>
					<p>Click on the "Print Registration Package" button below to open the registration package in a new browser window.</p>
				</div>
				<div style="text-align: center;padding: 10px;" id="button-home" class="pure-u-1">
					<a class="form_link_a" href="forms.php?rcd=<?php print $data['registration_code']; ?>&rid=<?php print $data['registration_id']; ?>" target="_new">Print Registration Package <i class="fa fa-hand-o-right"></i></a> 
				</div>
				<div style="text-align: center;padding: 10px;" id="button-home" class="pure-u-1">
					<a class="form_link_a" href="http://outed.limestone.on.ca/regfiles/winter.php">Register Another Student <i class="fa fa-hand-o-right"></i></a> 
				</div>
				<div style="text-align: center;padding: 10px;" id="button-home" class="pure-u-1">
					<a class="form_link_a" href="http://outed.limestone.on.ca">Return to Gould Lake Website <i class="fa fa-hand-o-right"></i></a> 
				</div>
			</div>
		</div>
	</div>

	<?php
	//EMAIL CODING
	$emailcontent = "If you need to re-print your registration package, please click on this link:\n";
	$emailcontent = $emailcontent."https://outed.limestone.on.ca/secure/regwinter/forms.php?rid=".$data['registration_id']."&rcd=".$data['registration_code']."\n\n";
	
	$emailcontent = $emailcontent."Student: ".$data['student_name_first']. " ". $data['student_name_last']. "\n";
	$emailcontent = $emailcontent."Course: ".$data['program_name']."\n\n";
	
	$emailcontent = $emailcontent."Session Choice 1: ";
	
	//GET INFO ON SESSION CHOICE 1
	$stmt = $conn->prepare("SELECT * FROM ss_sessions
													WHERE session_id = :session_id");
	$stmt -> bindValue(':session_id', $data['selected_session1']);
	$stmt-> execute();
	$session = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$emailcontent = $emailcontent.$session['session_program_code']. " - "
	.date("M. d",strtotime($session['session_start']))." - "
	.date("M. d, Y",strtotime($session['session_end']))."\n";
	
	$emailcontent=$emailcontent."\n";
	
	$emailcontent = $emailcontent."Application Reference Number: ".$data['registration_code']."\n";
	
	function errorHandler ($errno, $errstr, $errfile, $errline)
	{
	   switch ($errno)
	  {
	      case E_USER_WARNING:
	      case E_NOTICE:
	      case E_USER_NOTICE:
	         break;
		  case E_WARNING:
	      case E_CORE_WARNING:
	      case E_COMPILE_WARNING:
	      case E_USER_ERROR:
	      case E_ERROR:
	      case E_PARSE:
	      case E_CORE_ERROR:
	      case E_COMPILE_ERROR:
		  		
			global $mailerror;
			$mailerror="true";
			 
			default:
	         break;
	   } // switch
	} // errorHandler
	
	
	$email_final = "Thank you for submitting a registration for the Gould Lake Winter Program.\n
	Below are the details of your registration.\n  
	Please ensure that you submit the registration package, along with payment, to complete the registration process.\n\n";
	
	$email_final = $email_final.$emailcontent;
	$email_subject = "Gould Lake Winter Program Application";
	$email_header =  "From: outed@limestone.on.ca\r\n"
					."X-Mailer: PHP/".phpversion()."\r\n"
					."X-Priority: 3\r\n";
	$address_1 = $data['contact_email'];
	$mailerror="";
	
	set_error_handler('errorHandler');
	
	$sent = emailsend($address_1,$email_subject,$email_final,$email_header);
	$sent = emailsend('outed@limestone.on.ca','Winter Application Received',$email_final,$email_header);
	
	restore_error_handler();
	
	// Unset all of the session variables.
	$_SESSION = array();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
	}
	
	// Finally, destroy the session.
	session_destroy();
}
?>