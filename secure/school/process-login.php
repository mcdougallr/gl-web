<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

	$school_code = cleantext($_POST['school_code']);
	
	$stmt = $conn -> prepare("SELECT school_id, school_code FROM sy_schools WHERE school_code = :school_code");
	$stmt -> bindValue(':school_code', $school_code);
	$stmt -> execute();
	
	$school = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if (empty($school))
	{ ?>
		<script type="text/javascript">
			alert('Invalid School Code');
			location.href="index.php";
		</script> 
	<?php
	}
	else
	{
		$_SESSION['school_id'] = $school['school_id'];
		header("Location: index.php");
	}
?>