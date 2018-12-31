<?php

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'OUTED_mySQL';

/*** mysql password ***/
$password = 'outed';

try {
    $conn = new PDO("mysql:host=$hostname;dbname=outed", $username, $password);
		//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
?>