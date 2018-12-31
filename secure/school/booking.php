<?php
	include ('sch-header.php');
?>

<link rel="stylesheet" href="_gl-school.css">

<?php			
if (isset($_SESSION['school_id'])) 
{
	$stmt = $conn->prepare("SELECT * FROM sy_schools WHERE school_id = :school_id");
	$stmt->bindValue(':school_id', $_SESSION['school_id']);
	$stmt->execute();
	$school = $stmt->fetch(PDO::FETCH_ASSOC);

	?>
	<h1><?php print $school['school_name']; ?></h1>
	<div class="pure-g">			  	
	  	<div class="pure-u-1 pure-u-md-1-4">
            <div id="school-menu">
                <a href="index.php" class="school-menu-button" value="schedule"><i class="fa fa-calendar menu-icon"></i> scheduled classes</a>
                <a href="book.php" class="school-menu-button" value="booking"><i class="fa fa-pencil menu-icon"></i> book classes</a>
                <a href="http://outed.limestone.on.ca/aboutus/contactfind.php" class="school-menu-button" value="directions"><i class="fa fa-map-marker menu-icon"></i> directions to gl</a>
                <a href="http://outed.limestone.on.ca/teachers/overview.php" class="school-menu-button" value="info"><i class="fa fa-info menu-icon"></i> program info</a>
                <a href="../shared/logout.php" class="school-menu-button" value="logout"><i class="fa fa-sign-out menu-icon"></i> logout</a>
            </div>
		</div>
        <div id="temp" class="pure-u-1 pure-u-md-3-4">	    
        	<div id="bookings">
				<div id="booking-edit"></div>
				<div id="current-bookings"></div>
			</div>
		</div>
	</div>
  	<?php 
  	}
  	else 
  	{
	?>
	  	<section>
	  		<div id="school-login">
		  		<form id="school-login-form" class="pure-form pure-form-stacked" method="post" action="process-login.php">
		        	<label>Please enter your school code...</label>
		        	<input style="width: 150px;" type="text" name="school_code" />
		          	<button type="submit">Submit</button>
	        	</form>
	        </div>
	    </section>
    <?php 
	}
        
	include ('sch-footer.php');
?>
<script>

	$(document).ready(function(){
	
		$('#booking-edit').load('booking-form.php','',function(){
			$('#booking-edit').fadeIn('fast');
	   	});
		
		$('#current-bookings').load('booking-current.php','',function(){
			$('#current-bookings').fadeIn('fast');
	   	});     
	
	});

</script>