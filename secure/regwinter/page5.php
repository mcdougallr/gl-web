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
	 		});
	 	});	 
	 	
	 	$(".textarea_expand").on("keyup focus", function(){
      this.style.height = "1px";
      this.style.height = (this.scrollHeight) + "px"; 
    });	
	});
	
</script>