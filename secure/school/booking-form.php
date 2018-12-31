<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

if (isset($_POST['eid'])) {$event_id = cleantext($_POST['eid']);}
else {$event_id = "";}

$date = date('Y-m-d');
$year = date('Y');

//Get school info
$stmt = $conn->prepare("SELECT * FROM sy_schools WHERE school_id = :school_id");
$stmt->bindValue(':school_id', $_SESSION['school_id']);
$stmt->execute();
$school = $stmt->fetch(PDO::FETCH_ASSOC);

//See if booking is open
$stmt = $conn->prepare("SELECT season_name, booking_start, booking_end FROM sy_seasons WHERE DATE_FORMAT(:date, '%m-%d') between booking_start AND booking_end");
$stmt->bindValue(':date', $date);
$stmt->execute();
$booking_open = $stmt->fetch(PDO::FETCH_ASSOC);
if ($booking_open != "") {$book = 1;}
else {$book = 0;}

//See what season is coming up next
$stmt = $conn->prepare("SELECT booking_start, season_name, next_start, next_end FROM sy_seasons WHERE DATE_FORMAT(:date, '%m-%d') between next_start AND next_end");
$stmt->bindValue(':date', $date);
$stmt->execute();
$nextseason = $stmt->fetch(PDO::FETCH_ASSOC);
$booking_start = $year."-".$nextseason['booking_start'];

if ($nextseason['season_name'] == "Spring") {$quota = $school['S_quota']; $program_season = "FS";}
elseif ($nextseason['season_name'] == "Fall") {$quota = $school['F_quota']; $program_season = "FS";}
elseif ($nextseason['season_name'] == "Winter") {$quota = $school['W_quota']; $program_season = "W";}
else {$quota = ""; $program_season = "";}

//Calculate spots remaining from quota
$stmt = $conn->prepare("SELECT event_id FROM schedule_events
						LEFT JOIN schedule_visit ON schedule_events.event_type_id = schedule_visit.visit_id
					   	WHERE school_id = :school_id AND submit_date >= :submit_date AND event_type = :event_type");
$stmt->bindValue(':school_id', $_SESSION['school_id']);
$stmt->bindValue(':submit_date', $booking_start);
$stmt->bindValue(':event_type', "V");
$stmt->execute();
$visits = $stmt->fetchAll();
$visitcount = count($visits);

if ($visitcount >= $quota) {$full = 1;}
else {$full = 0;}

//Output appropriate form
if ($event_id != "" and $book == 1)
{
	$stmt = $conn->prepare("SELECT * FROM schedule_events
							   LEFT JOIN schedule_visit ON schedule_events.event_type_id = schedule_visit.visit_id
							   LEFT JOIN sy_programs ON sy_programs.program_id = schedule_visit.program_id
							   WHERE event_id = :event_id");
	$stmt->bindValue(':event_id', $event_id);
	$stmt->execute();
	$visit = $stmt->fetch(PDO::FETCH_ASSOC);
	
	?>
	<div id="update-booking-section" class="pure-g"> 
		<div class="pure-u-1">
			<h2>Update <?php print $nextseason['season_name']; ?> Booking</h2>
	    </div>
	    <form id="update-booking-form" class="pure-form pure-form-stacked">
			<input type="hidden" name="visit_id" value="<?php print $visit['visit_id']; ?>" />
        	<div class="pure-u-1-2 pure-u-sm-1-4">
            	<label>Teacher (First and Last Name)</label>
            	<input class="pure-input-1" type="text" name="teacher_name" value="<?php print $visit['teacher_name']; ?>" />
            </div>
            <div class="pure-u-sm-1-8">
            	<label>Grade</label>
            	<input class="pure-input-1" type="text" name="grade" value="<?php print $visit['grade']; ?>" required/>
            </div>
        		<div class="pure-u-1-4 pure-u-sm-1-8">
           	 	<label>Student #</label>
            	<input class="pure-input-1" type="text" name="student_num" value="<?php print $visit['student_num']; ?>" />
            </div>
						<div class="pure-u-3-4 pure-u-sm-1-2">
                <label>Program</label>
                <select class="pure-input-1" name="program_id" required>
                    <option value="">Select...</option>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM sy_programs WHERE visible = :visible ORDER BY sort_order");
												$stmt->bindValue(':visible', $program_season);
												$stmt->execute();
												$programs = $stmt->fetchAll();
						
                        foreach ($programs as $program)
                        {
                            print "<option value=".$program['program_id'];
                            if ($program['program_id'] == $visit['program_id'])
                                {print " selected";}
                            print ">".$program['program_name']."</option>";}
                    ?>
                </select>
            </div>
            <div class="pure-u-1">
	            	<label>Notes (any student concerns, unavaiable dates or logistical changes for your day at GL)</label>
            		<textarea class="pure-input-1" type="text" name="visit_notes"><?php print $visit['visit_notes']; ?></textarea>
            </div>
      		<div class="pure-u-1" style="text-align: center;">
            	<button type=submit class="plaintext-button"><i class="fa fa-check-circle"></i> update</button>      
        		<button id="update-cancel-button" class="plaintext-button"><i class="fa fa-times-circle"></i> cancel</button>  
      		</div>
    	</form>
	</div>
<?php
}
elseif ($event_id == "" and $book == 1 and $full == 0)
{?>
	<div id="new-booking-section" class="pure-g"> 
		<div class="pure-u-1">
			<h2>New <?php print $nextseason['season_name']; ?> Booking</h2>
    </div>
    <form id="new-booking-form" class="pure-form pure-form-stacked">
    	<input type="hidden" name="school_id" value="<?php print $_SESSION['school_id']; ?>" />
    	<div class="pure-u-1-2 pure-u-sm-1-4">
      	<label>Teacher (First and Last Name)</label>
      	<input class="pure-input-1" type="text" name="teacher_name" />
      </div>
      <div class="pure-u-1-2 pure-u-sm-1-8">
      	<label>Grade</label>
      	<input class="pure-input-1" type="text" name="grade" required/>
      </div>
    	<div class="pure-u-1-4 pure-u-sm-1-8">
     	 	<label>Student #</label>
      	<input class="pure-input-1" type="text" name="student_num" />
      </div>
			<div class="pure-u-3-4 pure-u-sm-1-2">
        <label>Program</label>
        <select class="pure-input-1" name="program_id" required>
          <option value="">Select...</option>
          <?php
              $stmt = $conn->prepare("SELECT * FROM sy_programs WHERE visible = :visible ORDER BY sort_order");
							$stmt->bindValue(':visible', $program_season);
							$stmt->execute();
							$programs = $stmt->fetchAll();
	
              foreach ($programs as $program)
              {print "<option value=".$program['program_id'].">".$program['program_name']."</option>";}
          ?>
        </select>
      </div>
      <div class="pure-u-1">
        	<label>Notes (any student concerns, unavaiable dates or logistical changes for your day at GL)</label>
      		<textarea class="pure-input-1" type="text" name="visit_notes"></textarea>
      </div>
  		<div class="pure-u-1" style="text-align: center;">
        	<button type=submit class="plaintext-button"><i class="fa fa-check-circle"></i> submit</button>    
  		</div>
    </form>
	</div>
<?php
}
elseif ($event_id == "" and $book == 1 and $full == 1)
{?>
	<div id="no-booking-section" class="pure-g"> 
		<div class="pure-u-1">
			<h2><?php print $nextseason['season_name']; ?> Booking Quota Full</h2>
        <p>You have reached your quota for this season's booking.</p>
    </div>
<?php
}
else
{
?>	
	<div id="no-booking-section" class="pure-g"> 
		<div class="pure-u-1">
			<h2><?php print $nextseason['season_name']; ?> Booking Is Closed</h2>
        <p style="margin: 15px 25px;">If you have missed the deadline or need to make changes to your booking, please call our office at 613-376-1433 or email us at outed@limestone.on.ca.
        </p>
    </div>
<?php
}
?>

<script>

	$(document).ready(function(){
	
		$("#update-cancel-button").click(function(e){
			e.preventDefault();
			$('#booking-edit').load('booking-form.php','',function(){
				$('#booking-edit').fadeIn('fast');
			}); 
		});
	
		$("#update-booking-form").submit(function(e){
			e.preventDefault();
			
			var senddata = $(this).serialize();
     		//alert (senddata);return false;

			$.ajax({
				type: "POST",
				url: "process-update-visit.php",
				data: senddata,
				success: function() 
				{
					$('#booking-edit').load('booking-form.php','',function(){
						$('#booking-edit').fadeIn('fast');
						$('#current-bookings').load('booking-current.php','',function(){
							$('#current-bookings').fadeIn('fast');
							alert('Booking Updated!');
							return false;
						}); 
					});	
					
				}
		 	});	
		});
		
		$("#new-booking-form").submit(function(e){
			e.preventDefault();
			
			var senddata = $(this).serialize();
     		//alert (senddata);return false;

			$.ajax({
				type: "POST",
				url: "process-new-visit.php",
				data: senddata,
				success: function() 
				{
					$('#booking-edit').load('booking-form.php','',function(){
						$('#booking-edit').fadeIn('fast');
						$('#current-bookings').load('booking-current.php','',function(){
							$('#current-bookings').fadeIn('fast');
							alert('Booking Submitted!'); return false;
						}); 
					});	
					
				}
		 	});	
		});
	
	});

</script>	