<?php
$page = 1;
include ('header.php');

//CREATE UNIQUE REGISTRATION CODE
if (!isset($_SESSION['registration_code']))
{
	$_SESSION['registration_code'] = generatePassword();
	for ($i=0; $i<1; $i)
	{
		$stmt = $conn->prepare("SELECT * FROM gl_registrations WHERE registration_code = :regcode");
		$stmt -> bindValue(':regcode', $_SESSION['registration_code']);
		$stmt -> execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($result))
		{
			$_SESSION['registration_code'] = generatePassword();
		}
		else
		{$i=1;}
	}
}

//LOAD REGISTRATION DATA INTO ARRAY
if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from gl_registrations where registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div id="gl-main">
	<div id="gl-wrapper">
		<p>Welcome to Gould Lake's Outdoor Leadership Program registration process.</p>
		<p>Please make your way through the steps. Use the links above to return to previous pages if needed.</p>
		<p style="font-weight: 400;">Let's get started...</p>
		<p>All communication for the upcoming summer will sent to the address given below. Please make sure it is one that is checked frequently.</p>
    <form novalidate class="pure-form pure-form-stacked" method="post" parsley-validate>
      <div class="pure-g">
      	<div class="pure-u-1 pure-u-md-1-2">
      		<div class="form-padding">
			      <label>Contact Email</label>
			      <input class="pure-input-1" id="student_email" name="student_email" type="email" onKeyPress="return noenter()" value="<?php if (isset($data['student_email'])) {print $data['student_email'];} ?>" required />
				  </div>
				</div>
			  <div class="pure-u-1 pure-u-md-1-2">
				  <div class="form-padding">
				  	<label>Confirm Email</label>
			      <input class="pure-input-1" name="student_email_2" type="email" onKeyPress="return noenter()" value="<?php if (isset($data['student_email'])) {print $data['student_email'];} ?>" required parsley-equalto="#student_email"/>
					</div>
				</div>
				<div class="pure-u-1 button-home">
	     		<button class="gl-submit-button">Save and continue to profile <i class="fa fa-hand-o-right"></i></button>
	      </div>
			</div>
		</form>
	</div>
</div>

<?php include ('footer.php'); ?>

<script>
	$(document).ready(function(){
	 	$(".gl-submit-button").click(function(){
	 		$(".pure-form").submit(function(e){
	    	e.preventDefault();
	 			if ( $(this).parsley().isValid() ) {
	 				$("#working").show();
	 				var senddata = $(this).serialize();
	     	 	//alert (senddata);return false;
	     	 	
	     	 	$.ajax({
		        type: "POST",
		        url: "process-1-email.php",
		        data: senddata,
		        success: function() {
							window.location.href = "page2.php";
	          }
	     	 	});	     	 	
	     	}
	 		});
	 	});	 	
	});
</script>