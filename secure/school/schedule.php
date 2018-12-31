<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$stmt = $conn->prepare("SELECT * FROM fws_schools WHERE school_id = :school_id");
$stmt->bindValue(':school_id', $_SESSION['school_id']);
$stmt->execute();
$school = $stmt->fetch(PDO::FETCH_ASSOC);

$today_date = date('Y-m-d');

//Fetch upcoming visits from database
$stmt = $conn->prepare("SELECT * FROM fws_visits 
					   LEFT JOIN fws_programs ON fws_programs.program_id = fws_visits.program_id
					   WHERE school_id = :school_id and visit_date >= :visit_date AND visit_date != \"0000-00-00\" AND visit_confirm = 1
					   ORDER BY visit_date");
$stmt->bindValue(':school_id', $school['school_id']);
$stmt->bindValue(':visit_date', $today_date);
$stmt->execute();
$visits = $stmt->fetchAll();

$visitcount = count($visits);
?>

<h2>Upcoming Scheduled Visits</h2>

<?php if ($visitcount > 0) { ?>
	
    <table class="visit-table pure-table pure-table-bordered">
            <tr>
              <th>Date</th>
              <th>Teacher</th>
              <th>Grade</th>
              <th>Program</th>
          </tr>
        <?php
            
        
      foreach ($visits as $visit)
      {
        print "<tr><td>".date('M d, Y', strtotime($visit['visit_date']))."</td>";
                print "<td>".$visit['teacher_name']."</td>";
                print "<td>".$visit['grade']."</td>";
                print "<td>".$visit['program_name']."</td></tr>";
        }
        ?>
    </table>
    
<?php 
}
else
{?>
<p>You have no visits currently scheduled to Gould Lake. Hopefully you can come out soon!</p>
<?php } ?>

<p style="margin: 15px 25px;">
<?php
if ($school['early'] == "Y") 
{ print "* Our files show that ".$school['school_name']." begins the school day prior to 9:00AM.  The expected <b>arrival time</b> for early schools is 9:30AM.  If you are going to be earlier or later than this, please let our office know so that we can make arrangments with Gould Lake staff.";}
else
{print "* Our files show that ".$school['school_name']." begins the school day after 9:00AM.  The expected <b>arrival time</b> for regular schedule schools is 10:00AM.  If you are going to be earlier or later than this, please let our office know so that we can make arrangments with Gould Lake staff.";}
?>
</p>