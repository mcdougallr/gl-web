<?php

	if (isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == 1)
	{			
		if ($_SESSION['staff_access'] < 2 and $_SESSION['refer'] != "s"){header ("Location: ../staff/index.php");}
		if ($_SESSION['staff_access'] < 3 and $_SESSION['refer'] == "sta"){header ("Location: ../staff/index.php");}
		if ($_SESSION['staff_access'] < 3 and $_SESSION['refer'] == "sya"){header ("Location: ../staff/index.php");}
		if ($_SESSION['staff_access'] < 3 and $_SESSION['refer'] == "sa"){header ("Location: ../staff/index.php");}
		if ($_SESSION['staff_access'] < 3 and $_SESSION['refer'] == "ses"){header ("Location: ../staff/index.php");}
		if ($_SESSION['staff_access'] < 4 and $_SESSION['refer'] == "sa"){header ("Location: ../staff/index.php");}
	}
	else
	{
		header("Location: ../shared/login.php?refer=". $_SESSION['refer']);
	}	
?>