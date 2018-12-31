<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

define("UPLOAD_DIR", "docs/");

// process file upload
if (!empty($_FILES["student_file"])) 
{
    if ($_FILES["student_file"]["error"] !== UPLOAD_ERR_OK) {
    	echo "<script>alert('An error occurred.')</script>";
		header("Location: index.php?sid=".$_SESSION['student_id']."#uploads");
   }
	else 
	{
	    // verify the file type
	    if ($_FILES['student_file']['type'] != 'application/pdf')
		{
			echo "<script>alert('File type is not permitted.');</script>";
			header("Location: index.php?sid=".$_SESSION['student_id']."#uploads");
		}
		else 
		{
			if($_FILES["student_file"]["size"] > 500000){
				echo "<script>alert('Files size is greater than 500kb.')</script>";
				header("Location: index.php?sid=".$_SESSION['student_id']."#uploads");
			}
			else
			{
			    // ensure a safe filename
			    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES["student_file"]["name"]);
			    
			    // don't overwrite an existing file
			    $i = 0;
			  	$parts = pathinfo($name);
			 	while (file_exists(UPLOAD_DIR . $name)) {
			     	$i++;
			   		$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			    }
			    
			    // preserve file from temporary directory
			    $success = move_uploaded_file($_FILES["student_file"]["tmp_name"], UPLOAD_DIR . $name);
			  	if (!$success) {
			    	echo "<script>alert('Unable to save file.')</script>";
					header("Location: index.php?sid=".$_SESSION['student_id']."#uploads");
				}
			}
	    }
	}
    
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
   
   $title = cleantext($_POST['file_title']);
      
   $stmt = $conn -> prepare("INSERT INTO ss_reg_files
							(file_student_id,file_title,file_name, file_date) 
							VALUES
							(:file_student_id,:file_title,:file_name, :file_date)");

	$stmt -> bindValue(':file_student_id', $_SESSION['student_id']);
	$stmt -> bindValue(':file_title', $title);
	$stmt -> bindValue(':file_name', $name);
	$stmt -> bindValue(':file_date', date("Y-m-d"));
	$stmt -> execute();
	
	header("Location: index.php?sid=".$_SESSION['student_id']."#uploads");
}