<?php 	
	$page = 6;
	include ('header.php');
		
	if ($_SESSION['registration_id'] != 0) {
		$stmt = $conn->prepare("select * from ss_registrations where registration_id = :reg_id");
		$stmt -> bindValue(':reg_id', $_SESSION['registration_id']);
		$stmt-> execute();
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	}

if ($_SESSION['registration_id'] == 0){header("Location: http://outed.limestone.on.ca/regfiles/");}	 
else {
?>
	<div id="gl-main">
		<?php include("menu.php"); ?>
		<div id="gl-wrapper">	
			<form novalidate class="pure-form pure-form-stacked" method="post" parsley-validate parsley-required-message="">
	      		<div class="pure-g">	      	
					<div class="pure-u-1">
			    		<p style="color: #326CAA;font-weight: 400;">Registration Submission</p>
			    		<p>You're almost there! Once you submit your registration, you will be unable to go back and make changes, so please ensure your registration is complete.  
		    			If you do submit the registration and realize there is an error, simply call our office (613.376.1433) and we can make any required changes.</p>
						<hr />
					</div>
					<div class="pure-u-1 pure-u-sm-3-4">
						<label>I acknowledge that all of the information entered is accurate and up-to-date.</label>
					</div>
					<div style="text-align: center;padding: 3px 0;" class="pure-u-1 pure-u-sm-1-4">
						<input style="width: 50px; height: 25px;vertical-align: -6px;" type=checkbox name="correct" required>Yes
					</div>
					<div class="pure-u-1"><hr /></div>
					<div class="pure-u-1 pure-u-sm-3-4">
						<label>I acknowledge that the submission of this registration does not guarantee acceptance into a program.</label>
					</div>
					<div style="text-align: center;padding: 3px 0;" class="pure-u-1 pure-u-sm-1-4">
						<input style="width: 50px; height: 25px;vertical-align: -6px;" type=checkbox name="correct" required>Yes
					</div>
					<div class="pure-u-1"><hr /></div>
					<div class="pure-u-1 pure-u-sm-3-4">
						<label>I understand that payment and a signed waiver must be received by the Gould Lake Outdoor Centre's office before a registration is considered complete.</label>
					</div>
					<div style="text-align: center;padding: 3px 0;" class="pure-u-1 pure-u-sm-1-4">
						<input style="width: 50px; height: 25px;vertical-align: -6px;" type=checkbox name="payment" required>Yes
					</div>
					<div class="pure-u-1"><hr /></div>
					<div class="pure-u-1 pure-u-sm-3-4">
						<label>I understand that this is a registration submission and that it does not guarantee a spot in the Gould Lake programs.</label>
					</div>
					<div style="text-align: center;padding: 3px 0;" class="pure-u-1 pure-u-sm-1-4">
						<input style="width: 50px; height: 25px;vertical-align: -6px;" type=checkbox name="confirm" required>Yes
					</div>
					<div class="pure-u-1"><hr /></div>
					<div class="pure-u-1 pure-u-sm-3-4">
	          			<label>I give permission for my child's photo and video image to be used on the Gould Lake twitter account and Gould Lake website.</label>
					</div>
					<div style="text-align: center;padding: 3px 0;" class="pure-u-1 pure-u-sm-1-4">
						<input style="width: 50px; height: 20px;vertical-align: -4px;" type=radio name="confirm_social_media" value="Y" required
						<?php 
							if ($data['confirm_social_media'] == "Y") 
							{print " checked";}
						?>
						>Yes
						<input style="width: 50px; height: 20px;vertical-align: -4px;" type=radio name="confirm_social_media" value="N" required
						<?php 
							if ($data['confirm_social_media'] == "N") 
							{print " checked";}
						?>
						>No
					</div>
					<div class="pure-u-1"><hr /></div>
					<div class="pure-u-1 pure-u-sm-3-4">
	          			<label>I give permission for my child's photo and video image to be used in the annual Gould Lake Slideshow and for Gould Lake promotional materials.</label>
					</div>
					<div style="text-align: center;padding: 3px 0;" class="pure-u-1 pure-u-sm-1-4">
						<input style="width: 50px; height: 20px;vertical-align: -4px;" type=radio name="confirm_photo" value="Y" required
						<?php 
							if ($data['confirm_photo'] == "Y") 
							{print " checked";}
						?>
						>Yes
						<input style="width: 50px; height: 20px;vertical-align: -4px;" type=radio name="confirm_photo" value="N" required
						<?php 
							if ($data['confirm_photo'] == "N") 
							{print " checked";}
						?>
						>No
					</div>
					<div class="form-spacer"></div>
					<div class="pure-u-1 button-home">
			     	<button style="padding-bottom: 0px;" class="gl-submit-button">Submit registration <i class="fa fa-hand-o-right"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<?php include ('footer.php'); 
}
?>

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
		        url: "process-6-confirm.php",
		        data: senddata,
		        success: function() {
							window.location.href = "page7.php";
	          }
	     	 	});	     	 	
	     	}
	 		});
	 	});	 
	});
	
</script>