<?php
	if (!isset($_SESSION['logged_in']) and $_SESSION['logged_in'] != 1)
	{	
		header("Location: ../shared/login.php?refer=".$page);
	}	
?>