<?php
session_start();

include ('../shared/clean.php');

if (isset($_GET['refer'])) {$refer = cleantext($_GET['refer']);}
else {$refer = "staff";}

if ($refer == "eq") {$page = "eq";}
elseif ($refer == "food") {$page = "food";}
elseif ($refer == "log") {$page = "logistics";}
elseif ($refer == "sy") {$page = "school-year";}
elseif ($refer == "sya") {$page = "school-year-admin";}
elseif ($refer == "sta") {$page = "student-admin";}
elseif ($refer == "sa") {$page = "staff-admin";}
elseif ($refer == "sta") {$page = "student-admin";}
elseif ($refer == "ses") {$page = "session-admin";}
else {$page = "staff";}

?>

<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="../../scripts/pure/pure-min.css">  
		<link rel="stylesheet" href="../shared/_gl-login.css">
		<script src="../../scripts/jquery/jquery-1.11.0.min.js"></script> 
		<script src="../shared/jquery-ui-1.10.3.custom.js"></script> 
		<title>Gould Lake Login</title>
	</head>
	<body>
        <div id="gl-base-wrapper" class="pure-g">
            <div id="gl-login" class="pure-u-1">
            <h1>Gould Lake Staff Login</h1>
            <form id="login-form" class="pure-form pure-form-stacked" method="post">      
                <label>Email</label>
                <input id="staff_login" class="pure-input-1" name="staff_login" type="text" placeholder="Email">
                <label>Password</label>
                <input id="staff_password"class="pure-input-1" name="staff_password" type="password" placeholder="Password">
                <button id="login-button" class="class-details" type="submit" name="loginbutton">LOG IN</button>
            </form>
          </div>
        </div>
    </body>
</html>
<script>
	$('#login-form').submit(function(e) {
		e.preventDefault();
		
		var sl = $('#staff_login').val();
		var sp = $('#staff_password').val();

		$.ajax({
			type: "POST",
			url: "process-pw.php",
			data: {"staff_login": sl, "staff_password": sp},
			dataType: "json",
			success: function(data) 
			{
				if (data.login == 0){alert("Log in failed.");}
				else {
					//alert(data.staff_id);return false;
					$.ajax({
						type: "POST",
						url: "process-update.php",
						data: {"staff_id": data.staff_id},
						success: function() 
						{window.location = "../<?php print $page; ?>/index.php";}
					});
				}
			}
		});
	});
</script>