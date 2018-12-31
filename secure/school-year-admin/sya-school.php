<?php
include ("sya-header.php"); 

$_SESSION['page_refer'] = "sya-school";

if (isset($_GET['sid'])) {$school_id = $_GET['sid'];}
else {header ("Location: index.php");}

$stmt = $conn->prepare("SELECT season_code, season_classes FROM sy_season WHERE season_code = 'S'");
$stmt->execute();
$springclassesarr = $stmt->fetch(PDO::FETCH_ASSOC);
$springclasses = $springclassesarr['season_classes'];

$stmt = $conn->prepare("SELECT season_code, season_classes FROM sy_season WHERE season_code = 'W'");
$stmt->execute();
$winterclassesarr = $stmt->fetch(PDO::FETCH_ASSOC);
$winterclasses = $winterclassesarr['season_classes'];

$stmt = $conn->prepare("SELECT season_code, season_classes FROM sy_season WHERE season_code = 'F'");
$stmt->execute();
$fallclassesarr = $stmt->fetch(PDO::FETCH_ASSOC);
$fallclasses = $fallclassesarr['season_classes'];

$stmt = $conn->prepare("SELECT SUM(population) FROM sy_schools WHERE school_div = \"E\"");
$stmt->execute();
$elempoparr = $stmt->fetch(PDO::FETCH_ASSOC);
$elempop = $elempoparr['SUM(population)'];

?>

<link rel="stylesheet" href="_sya-school.css">

<!-- Start Main Window -->
<?php
$stmt = $conn->prepare("SELECT * FROM sy_schools WHERE school_id = :school_id");
$stmt->bindValue(':school_id', $school_id);
$stmt->execute();
$school = $stmt->fetch(PDO::FETCH_ASSOC); ?>

<div class="pure-g" style="padding-top: 5px;"> 
	<div class="pure-u-1 pure-u-md-1-3  pure-u-lg-3-8">
		<form id="school_form" class="pure-form pure-form-stacked" method="post" action="">
			<div class="pure-g"> 
				<div class="pure-u-1 pure-u-sm-1-2 pure-u-md-1">
					<div class="input-padding">
        				<label>School Name</label>
          				<input class="pure-input-1 gl-input" name="school_name" type="text" onkeypress="return noenter()" value="<?php print $school['school_name']; ?>">
        			</div>
        		</div>
        		<div class="pure-u-1 pure-u-sm-1-2"> 
        			<div class="input-padding">
          				<label>School Code</label>
          				<input id="school_code" class="pure-input-1 gl-input" type="text" name="school_code" onkeypress="return noenter()" value="<?php print $school['school_code']; ?>">
        			</div>
        		</div>
        		<div class="pure-u-1-2"> 
        			<div class="input-padding">
			          	<label>Type</label>
			          	<select class="pure-input-1 gl-input" onkeypress="return noenter()" name="school_div" placeholder="School Type">
			            	<option value="E" <?php	if($school['school_div']=="E"){print " selected";} ?>>Elem</option>
			            	<option value="S" <?php	if($school['school_div']=="S"){print " selected";} ?>>Sec</option>
			          	</select>
        			</div>
        		</div>
        		<div class="pure-u-1-2"> 
        			<div class="input-padding">
          				<label>Early?</label>
			          	<select class="pure-input-1 gl-input" onkeypress="return noenter()" name="early" placeholder="Early?">
			            	<option value="Y" <?php	if($school['early']=="Y"){print " selected";} ?>>Yes</option>
			            	<option value="N" <?php	if($school['early']=="N"){print " selected";} ?>>No</option>
			          	</select>
        			</div>
        		</div>
        		<div class="pure-u-1 pure-u-sm-1-4 pure-u-md-1-2"> 
        			<div class="input-padding">
          				<label>Population (<?php print ROUND($school['population']/$elempop*($fallclasses+$springclasses+$winterclasses)); ?>)</label>
          				<input id="population" class="pure-input-1 gl-input" type="text" name="population" onkeypress="return noenter()" value="<?php print $school['population']; ?>">
        			</div>
        		</div>
	        	<div class="pure-u-1-3 pure-u-sm-1-4 pure-u-md-1-3"> 
	        		<div class="input-padding">
          				<label>Fall</label>
          				<input id="fall_quota" class="pure-input-1 gl-input" type="text" name="F_quota" onkeypress="return noenter()" value="<?php print $school['F_quota']; ?>">
        			</div>
        		</div>
        		<div class="pure-u-1-3 pure-u-sm-1-4 pure-u-md-1-3"> 
        			<div class="input-padding">
          				<label>Winter</label>
          				<input id="winter_quota" class="pure-input-1 gl-input" type="text" name="W_quota" onkeypress="return noenter()" value="<?php print $school['W_quota']; ?>">
        			</div>
        		</div>
        		<div class="pure-u-1-3 pure-u-sm-1-4 pure-u-md-1-3"> 
        			<div class="input-padding">
          				<label>Spring</label>
          				<input id="spring_quota" class="pure-input-1 gl-input" type="text" name="S_quota" onkeypress="return noenter()" value="<?php print $school['S_quota']; ?>">
        			</div>
        		</div>
        		<div class="pure-u-1"> 
        			<div class="input-padding">
          				<label>Notes</label>
          				<textarea id="school_notes" class="pure-input-1 gl-input" name="school_notes" onkeypress="return noenter()"><?php print $school['school_notes']; ?></textarea>
        			</div>
        		</div>
        	</div>
    	</form>
   </div>
	<div class="pure-u-1 pure-u-md-2-3  pure-u-lg-5-8">
		<label class="school-booking-label">Bookings</label>
		<div class="school-bookings">
			<?php	   
			$stmt = $conn->prepare("SELECT * FROM schedule_events
									LEFT JOIN schedule_visit ON schedule_visit.visit_id = schedule_events.event_type_id
									LEFT JOIN sy_schools ON sy_schools.school_id = schedule_visit.school_id
									LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
									WHERE event_type = 'V' AND schedule_visit.school_id = :school_id AND visit_confirm = 1
									ORDER BY event_date DESC");
			$stmt->bindValue(':school_id', $school_id);
			$stmt->execute();
			$visits = $stmt->fetchAll();?>
		
			<table class="school-table">
				<tr>
					<td style="text-align: left;" colspan=2>
						<a href="../schedule/edit-visit.php" style="color: #222;text-decoration: none;">Add Visit...</a>
					</td>
					<td class="school-table-full" colspan=2></td>
				</tr>
				<?php
				$count = 0;
				foreach ($visits as $visit)
				{
					print "<tr>";			
					print "<td style=\"text-align: left;\"><a href=\"../schedule/edit-visit.php?eid=".$visit['event_id']."\" class=\"school-button\" ";
					if ($visit['event_date'] > date("Y-m-d"))
						{print "style=\"color: #222;\" ";}
					print ">".$visit['event_date']."</a></td>";
					print "<td style=\"text-align: left;";
					if ($visit['event_date'] > date("Y-m-d"))
						{print "color: #222;";}
					print "\">".$visit['program_name']."</td>";
					print "<td class=\"booking-table-full\" ";
					if ($visit['event_date'] > date("Y-m-d"))
						{print "style=\"color: #222;\"";}
					print ">".$visit['teacher_name']."</td>";				
					print "<td class=\"booking-table-full\" ";
					if ($visit['event_date'] > date("Y-m-d"))
						{print "style=\"color: #222;\"";}
					print ">".$visit['grade']."</td></tr>";	
					$count ++;			
				}?>
			</table>
		</div>
	</div>		
</div>
	
<?php include("sya-footer.php"); ?>
	
<script>

	$(document).ready(function(){
		
		// PROCESS FORM INPUT
		$('.gl-input').change(function(e) {
			name = $(this).attr("name");
			val = $(this).val();			
			$(this).css('background', '#39F');
			//alert(name+" "+val);
			$.ajax({
				type : "POST",
				url : "process-input.php",
				data : {
					name : name,
					val : val,
					table : "sy_schools",
					id_name : "school_id",
					id: <?php print $school_id; ?>
				},
				success : function() {
					$('.gl-input').delay(300)
				    .queue(function() {
				        $('.gl-input').css('background', '#FFF').dequeue();
				    });
				}
			});
		});
		
	});

</script>