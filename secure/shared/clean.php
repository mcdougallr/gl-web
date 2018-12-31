<?php

function cleantext ($value)
{
	$value = strip_tags($value);
	$value = htmlspecialchars($value);
	return $value;
}
?>