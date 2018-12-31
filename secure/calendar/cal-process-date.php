<?php
session_start();

function cleantext ($value)
{
	$value = strip_tags($value);
	$value = htmlspecialchars($value);
	return $value;
}

if (!isset($_SESSION['date'])){$_SESSION['date'] = date('Y-m-d');}
$day = date("d",strtotime($_SESSION['date']));
$month = date("m",strtotime($_SESSION['date']));
$year = date("Y",strtotime($_SESSION['date']));

if (isset($_POST['date']))
{$date = cleantext($_POST['date']);}
else
{
	if (isset($_POST['day'])) 
	{
		$day = cleantext($_POST['day']);
		$day = sprintf("%02s", $day);
	}
	
	if (isset($_POST['month'])) 
	{
		$month = cleantext($_POST['month']);
		$month = sprintf("%02s", $month);
	}
	
	if (isset($_POST['year'])) 
	{
		$year = cleantext($_POST['year']);
	}

	$date = $year."-".$month."-".$day;
}

$_SESSION['date'] = $date;