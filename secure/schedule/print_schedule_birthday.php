<?php

$stmt = $conn->prepare("SELECT staff_name_common, staff_name_last FROM staff where
						DATE_FORMAT(staff_dob, '%m-%d') = DATE_FORMAT(:staff_dob, '%m-%d')");
$stmt->bindValue(':staff_dob', $_SESSION['date']);
$stmt->execute();
$BDs = $stmt->fetchAll();

if (count($BDs) > 0) 
{?>
	<div class="pure-g schedule-item">
		<div class="pure-u-1-12 icon"><i style="color: #C69;" class="fa fa-birthday-cake"></i></div>					
		<div class="pure-u-11-12">
			<div class="details">
				<?php 
				foreach ($BDs as $BD)
				{print "<div class=\"title\">".$BD['staff_name_common']." ".$BD['staff_name_last'][0]."'s Birthday!</div>";}
				?>
			</div>
		</div>	
	</div>
	<?php
	$eventcount++;
}