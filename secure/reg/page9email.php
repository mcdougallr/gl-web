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
	//EMAIL CODING
	$emailcontent = "If you need to re-print your registration package, please click on this link:\n";
	$emailcontent = $emailcontent."https://outed.limestone.on.ca/secure/reg/forms.php?rid=".$data['registration_id']."&rcd=".$data['registration_code']."\n\n";
	
	$emailcontent = $emailcontent."Student: ".$data['student_name_first']. " ". $data['student_name_last']. "\n";
	
	$emailcontent = $emailcontent."School for Fall 2019: ".$data['student_school_fall']. "\n";
	$emailcontent = $emailcontent."Current Grade: ".$data['student_grade']. "\n";
	$emailcontent = $emailcontent."Course: ".$data['program_name']."\n\n";
	
	$emailcontent = $emailcontent."Session Choice 1: ";
	
	//GET INFO ON SESSION CHOICE 1
	$stmt = $conn->prepare("SELECT * FROM ss_sessions
													WHERE session_id = :session_id");
	$stmt -> bindValue(':session_id', $data['selected_session1']);
	$stmt-> execute();
	$session = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$emailcontent = $emailcontent.$session['session_program_code']. " ". $session['session_number']." - "
	.date("M. d",strtotime($session['session_start']))." - "
	.date("M. d, Y",strtotime($session['session_end']))."\n";
	
	$emailcontent=$emailcontent."\n";
	
	if ($data['selected_session2']!=0)
	{
		$emailcontent = $emailcontent."Session Choice 2: ";
	
			//GET INFO ON SESSION CHOICE 2
			$stmt = $conn->prepare("SELECT * FROM ss_sessions
															WHERE session_id = :session_id");
			$stmt -> bindValue(':session_id', $data['selected_session2']);
			$stmt-> execute();
			$session = $stmt->fetch(PDO::FETCH_ASSOC);
	
		$emailcontent = $emailcontent.$session['session_program_code']. " ". $session['session_number']." - "
		.date("M. d",strtotime($session['session_start']))." - "
		.date("M. d, Y",strtotime($session['session_end']))."\n";
	
		$emailcontent = $emailcontent."\n";
	}
	
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
	
	
	$email_final = "Thank you for submitting a registration for the Gould Lake Summer Programs.\n
	Below are the details of your registration.\n  
	Please ensure that you submit the registration package, along with payment, to complete the registration process.\n\n";
	
	$email_final = $email_final.$emailcontent;
	$email_subject = "Gould Lake Program Application";
	$email_header =  "From: outed@limestone.on.ca\r\n"
					."X-Mailer: PHP/".phpversion()."\r\n"
					."X-Priority: 3\r\n";
	$address_1 = $data['student_email'];
	$mailerror="";
	
	set_error_handler('errorHandler');
	
	$sent = emailsend($address_1,$email_subject,$email_final,$email_header);
	$sent = emailsend('outed@limestone.on.ca','Summer Application Received',$email_final,$email_header);
	
	restore_error_handler();
}
?>