<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$staff = cleantext($_POST['staff']);

define("UPLOAD_DIR", "docs/");

// process file upload
if (!empty($_FILES["staff_file"])) 
{
    if ($_FILES["staff_file"]["error"] !== UPLOAD_ERR_OK) {
    	echo "<script>alert('An error occurred.')</script>";
		header("Location: index.php?sid=".$staff."#uploads");
   }
	else 
	{
	    // verify the file type
	    if ($_FILES['staff_file']['type'] != 'application/pdf' AND $_FILES['staff_file']['type'] != 'image/jpeg')
		{
			echo "<script>alert('File type is not permitted.');</script>";
			header("Location: index.php?sid=".$staff."#uploads");
		}
		else 
		{
			if($_FILES["staff_file"]["size"] > 1000000){
				echo "<script>alert('Files size is greater than 1000kb.');</script>";
				header("Location: index.php?sid=".$staff."#uploads");
			}
			else
			{
			    // ensure a safe filename
			    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES["staff_file"]["name"]);
			    
			    // don't overwrite an existing file
			    $i = 0;
			  	$parts = pathinfo($name);
			 	while (file_exists(UPLOAD_DIR . $name)) {
			     	$i++;
			   		$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			    }
			    
			    // preserve file from temporary directory
			    $success = move_uploaded_file($_FILES["staff_file"]["tmp_name"], UPLOAD_DIR . $name);
			  	if (!$success) {
			    	echo "<script>alert('Unable to save file.')</script>";
					header("Location: index.php?sid=".$staff."#uploads");
				}
			}
	    }
	}
    
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
   
   $title = cleantext($_POST['file_title']);
      
   $stmt = $conn -> prepare("INSERT INTO staff_files
														(file_staff_id,file_title,file_name,file_date) 
														VALUES
														(:file_staff_id,:file_title,:file_name,:file_date)");

	$stmt -> bindValue(':file_staff_id', $staff);
	$stmt -> bindValue(':file_title', $title);
	$stmt -> bindValue(':file_name', $name);
	$stmt -> bindValue(':file_date', date("Y-m-d"));
	
	$stmt -> execute();
	
	header("Location: index.php?sid=".$staff."#uploads");
}