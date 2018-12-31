<?php

function generatePassword()
{
		$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		$nums = '23456789';
		$pwd = 0;

		mt_srand(microtime() * 1000000);

            $key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
	        $key = mt_rand(0,strlen($chars)-1);
            $pwd = $pwd . $chars[$key];
			$key = mt_rand(0,strlen($chars)-1);
            $pwd = $pwd . $chars[$key];
			$key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
			$key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
			$key = mt_rand(0,strlen($chars)-1);
            $pwd = $pwd . $chars[$key];
			$key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
			return $pwd;
}


function generatePasswordManual()
{
		$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		$nums='23456789';

		mt_srand(microtime() * 1000000);

            $key = mt_rand(0,strlen($nums)-1);
            $pwd = "MMM";
			$key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
			$key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
			$key = mt_rand(0,strlen($chars)-1);
            $pwd = $pwd . $chars[$key];
			$key = mt_rand(0,strlen($nums)-1);
            $pwd = $pwd . $nums[$key];
			return $pwd;
}

function emailsend ($to, $subject, $text, $header)
{
	$send=mail ($to, $subject, $text, $header);
	return $send;
}

?>

<script language=javascript>
	function noenter() {
	return !(window.event && window.event.keyCode == 13); }
</script>