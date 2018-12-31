<?php
session_start();

include ('../shared/functions.php');

$logout = logout();

header("Location: http://outed.limestone.on.ca/index.php");

function logout()
{
	// Unset all of the session variables.
	$_SESSION = array();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (isset($_COOKIE[session_name()]))
	{setcookie(session_name() ,'',0);}
	
	// Finally, destroy the session.
	session_destroy();
}

?>
	
