<?php 	
	$page = 5;
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
			    		<p style="color: #326CAA;font-weight: 400;">Siblings</p>
			    		<p>Does <?php print $data['student_name_common']; ?> have any siblings registering for Gould Lake courses in 2019? Please provide their names and the course for which they are registering.</p>
						<textarea style="height: 2.3em" name="student_siblings" class="pure-input-1 textarea_expand"><?php if (isset($data['student_siblings'])) {print $data['student_siblings'];} ?></textarea>
			    		<hr />
				    </div>
				    <div class="pure-u-1">
				    	<p style="color: #326CAA;font-weight: 400;">Experience</p>
						<p>Please enter any Gould Lake courses <?php print $data['student_name_common']; ?> has completed in previous summers.</p>
					</div>
					<?php 
					
						$programarray = array("oe"=>"Outdoor Escape","gap"=>"GAP","q"=>"Quest","olp"=>"OLP","outreach"=>"Outreach","op"=>"Outdoor Pursuits","os"=>"Outdoor Skills","solo"=>"SOLO","wic"=>"WIC","lt"=>"Long Trail","kic"=>"KIC");
						$j = 0;
						foreach($programarray as $key =>$programname)
						{
							print "<div class=\"pure-u-1 pure-u-sm-1-3\"><div class=\"form-padding\">";															
							print "<label>".$programname."</label>";
							print "<select class=\"pure-input-1\" name=\"completed_".$key."_year\"><option value=\"\">Year</option>";
				      		$startyear = date("Y");
						  	for($i=-6; $i<=0; $i++)
							{
								$printyear = $startyear + $i;
								print $i."<option value=".$printyear;
								$program_year ="completed_".$key."_year";
								if (isset($data[$program_year]))
									{if ($data[$program_year] == $printyear)
									{print " selected";}}
								print ">".$printyear."</option>";
							}
							print "</select></div></div>";
							$j++;
						}
					?>
					<div class="pure-u-1">
						<p>If <?php print $data['student_name_common']; ?> is new to our programs, please give a brief description of <?php if ($data['student_sex'] == "Male"){print "his";} else {print "her";} ?> past experience with outdoor activities and camping.</p>
	  					<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_experience"><?php if (isset($data['student_experience'])) {print $data['student_experience'];} ?></textarea>
					</div>
					<div class="pure-u-1">
						<hr />
						<p style="color: #326CAA;font-weight: 400;">Swim Ability</p>
						<p>To ensure student safety, Limestone District School Board policy does not allow non-swimmers 
							to participate in overnight canoe/kayak trips.  LDSB policy requires that a student is able to 
						swim at least 50 metres, perform a rolling entry (backward or forward) and tread water for a minimum of 1 minute. </p>
	  					<div class="pure-g">
		   					<div class="pure-u-1 pure-u-sm-3-4">
								<label>Can <?php print $data['student_name_common']; ?> swim 50m, perform a rolling entry (backward or forward) and tread water for a minimum of 1 minute without assistance?</label>
					    	</div>
		  					<div class="pure-u-1 pure-u-sm-1-4 input-radio-align">
								<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_swimming" type="radio" value="Yes" <?php if (isset($data['student_swimming'])) {if ($data['student_swimming'] == "Yes") {print " checked";}} ?>  required>
						      	<label style="display: inline;">Yes</label>
						      	<input style="height: 17px;width: 30px;vertical-align: -3px;" name="student_swimming" type="radio" value="No" <?php if (isset($data['student_swimming'])) {if ($data['student_swimming'] == "No") {print " checked";}} ?>  required>
								<label style="display: inline;">No</label>
							</div>
						</div>
			     		<label style="font-size:.75em; font-variant:normal;"><em>Please state the student's current swimming ability (eg. can swim well, has taken Bronze Medallion, etc.)</em></label>
	  					<textarea style="height: 2.3em" class="pure-input-1 textarea_expand" name="student_swimming_details"><?php if (isset($data['student_swimming_details'])) {print $data['student_swimming_details'];} ?></textarea>
					</div>
					<div class="pure-u-1 button-home">
			     		<button class="gl-submit-button">Save and continue to programs <i class="fa fa-hand-o-right"></i></button>
					</div>
				</div>
			</form>
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
		  	if ($("input[name=student_swimming]:checked").val() == "No")
		  	{
		  		alert("Students that cannot complete the swim test cannot participate in overnight canoe activites. Please call our office for further details (613-376-1433).")
		  		return false;
		  	}
		  	else
		  	{
					if ( $(this).parsley().isValid() ) {
						$("#working").show();
						var senddata = $(this).serialize();
			   	 	//alert (senddata);return false;
			   	 	
			   	 	$.ajax({
			        type: "POST",
			        url: "process-5-additional.php",
			        data: senddata,
			        success: function() {
								window.location.href = "page6.php";
		          }
		     	 	});	     	 	
		     	}
		   	}
	 		});
	 	});	 
	 	
	 	$(".textarea_expand").on("keyup focus", function(){
      this.style.height = "1px";
      this.style.height = (this.scrollHeight) + "px"; 
    });	
	});
	
</script>