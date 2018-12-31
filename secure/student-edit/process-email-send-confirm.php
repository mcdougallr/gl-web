<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$email_id = "11";

//GRAB ALL EMAIL ERRORS RATHER THAN CRASHING
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

//GET EMAIL INFO
$stmt = $conn->prepare("SELECT * FROM ss_emails WHERE email_id = :email_id");
$stmt->bindValue(':email_id', $email_id);
$stmt->execute();
$email = $stmt->fetch(PDO::FETCH_ASSOC);

$email_type = $email['email_type'];
$email_followup = $email['email_followup'];
$email_text = $email['email_text'];
$email_subject = $email['email_subject'];
$email_header =		 "From: outed@limestone.on.ca\r\n"
					."Reply-To: outed@limestone.on.ca\r\n"
					."X-Mailer: PHP/".phpversion()."\r\n"
					."X-Priority: ".$email['email_priority'];

//GET STUDENT INFO
$stmt = $conn->prepare("SELECT * FROM ss_registrations 
						LEFT JOIN ss_programs ON ss_registrations.selected_program = ss_programs.program_id 
						LEFT JOIN ss_sessions ON ss_registrations.accepted_session = ss_sessions.session_id
						WHERE registration_id = :registration_id");
$stmt->bindValue(':registration_id', $_SESSION['student_id']);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$emailaddress = $student['contact_email'];

$mailerror="";

//Change pronouns for male/female
if($student['student_sex']=="Male")
{
	$himher="him";
	$hisher="his";
	$heshe="he";
}
else
{
	$himher="her";
	$hisher="her";
	$heshe="she";
}

$search=array("him/her", "his/her", "he/she");
$replace=array($himher, $hisher, $heshe);
$email_text_final=str_replace($search,$replace,$email_text);

	//Complete substitutions for other fields
	$subquery = "SELECT * FROM ss_email_substitutions";
	foreach ($conn->query($subquery) as $subs)
	{
		$sub_field = $subs['substitution_field'];
		$email_text_final=str_replace($subs['substitution_variable'],$student[$sub_field], $email_text_final);
		$email_subject_final=str_replace($subs['substitution_variable'],$student[$sub_field], $email_subject);
	}
//SEND EMAILS
set_error_handler('errorHandler');

$sent = emailsend($emailaddress,$email_subject_final,$email_text_final,$email_header);

restore_error_handler();
	
if($mailerror!="true")
{
	//PERFORM FOLLOW-UP SCRIPT
	$stmt = $conn->prepare("UPDATE ss_registrations
							SET status_email_sent_accept=1
							WHERE registration_id = :registration_id");
	$stmt->bindValue(':registration_id', $_SESSION['student_id']);
	$stmt->execute();
}
else
{
	$sent = emailsend("mcdougallr@limestone.on.ca","Email Sending Error",$email_text_final,$email_header);
}

?>