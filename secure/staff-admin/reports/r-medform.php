<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="medprint.css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<title>Gould Lake Staff Med Form Print</title>

<?php
session_start();

	include ('../../shared/dbconnect.php');
	include ('../../shared/clean.php');
	include ('../../shared/authenticate.php');
	
?>

</head>
<body>
<?php

$count = count ($_POST['staff_id']);
$j = 0;

for ($i = 0; $i<$count; $i++)
	{

	if (isset($_POST['printchecked'][$i]) and $_POST['printchecked'][$i] == 1)
	{
		$sid = cleantext($_POST['staff_id'][$i]);
		$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
		$stmt->bindValue(':staff_id', $sid);
		$stmt->execute();						
		$staff = $stmt->fetch(PDO::FETCH_ASSOC); 
		
		?>

		<div id="wrapper">
			<div id="header">
  			<h1>
				<?php 
					print $staff['staff_name_last'].", ".$staff['staff_name_common']; 
				?>
            </h1>
    	</div>
			<div id="medform-details">
      	<table>
      		<tr>
      			<td width="50%">
	      			<label>DOB: </label>
							<?php print $staff['staff_dob']; ?><br>
              <label>Health Card #: </label><?php print $staff['staff_health_card']; ?><br>
              <label>Email: </label><?php print $staff['staff_email']; ?><br>
              <label>Home #: </label><?php print $staff['staff_phone_home']; ?><br>
              <label>Cell #: </label><?php print $staff['staff_phone_cell']; ?><br>
              <br><br>
                            
              <label>Address</label><br>
              <?php print $staff['staff_p_address']; ?><br>
              <?php print $staff['staff_p_city'].", ".$staff['staff_p_province']; ?><br>
              <?php print $staff['staff_p_postalcode']; ?>
            </td>
            <td width="50%">
                <label>Emergency Contact - <?php print $staff['staff_econtact_relationship']; ?></label><br>
                <label>Name: </label><?php print $staff['staff_econtact_name_first']." ".$staff['staff_econtact_name_last']; ?><br>
                <label>Home: </label><?php print $staff['staff_econtact_phone_day']; ?><br>
				<label>Cell: </label><?php print $staff['staff_econtact_phone_evening']; ?><br>
            </td></tr>
            <tr>
	      									<td colspan=3>
              
								<label>Med Info </label><br>
                <?php 
                if ($staff['staff_health_conditions'] != "") {print "&nbsp;Health Conditions: ".$staff['staff_health_conditions']."<br>";}
								if ($staff['staff_health_injuries'] != "") {print "&nbsp;Meds: ".$staff['staff_health_injuries']."<br>";}
								
              if ($staff['staff_health_dietary'] != "") { ?><label>Dietary </label><br><?php print "&nbsp;".$staff['staff_health_dietary']; }?>
          	</td>	
            </tr>
        </table>
        <?php $j++; ?>
     	</div>
  	</div> 
    <br>                 
		<?php 
	}
}?>
</body>
