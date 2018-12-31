 <?php
 if ($page > 0 and $page < 9)
 {?>
  	<nav>
  		<h3>Navigation</h3>
    	<div class="nav-text">
    		<?php
	    	if ($_SESSION['page1'] == 0 && $page != 1) {print "<div class=\"pageincomplete\">Email</div>";}
			elseif ($page == 1) {print "<div class=\"pagecurrent\">Email<i class=\"fa fa-arrow-circle-right\"></i></div>";}
	    	else {print "<a class=\"pagecomplete\" href=\"index.php\">Email<i class=\"fa fa-check-circle\"></i></a>";}
			?>
		</div>
		<div class="nav-text">		    	
    		<?php
	    	if ($_SESSION['page2'] == 0 && $page != 2) {print "<div class=\"pageincomplete\">Profile</div>";}
			elseif ($page == 2) {print "<div class=\"pagecurrent\">Profile<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page2.php\">Profile<i class=\"fa fa-check-circle\"></i></a>";}
			?>
		</div>
		<div class="nav-text">		    	
	    	<?php
	    	if ($_SESSION['page3'] == 0 && $page != 3) {print "<div class=\"pageincomplete\">Contact</div>";}
			elseif ($page == 3) {print "<div class=\"pagecurrent\">Contact<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page3.php\">Contact<i class=\"fa fa-check-circle\"></i></a>";}  
			?>
		</div>
		<div class="nav-text">		    	  	
    		<?php
	    	if ($_SESSION['page4'] == 0 && $page != 4) {print "<div class=\"pageincomplete\">Medical</div>";}
			elseif ($page == 4) {print "<div class=\"pagecurrent\">Medical<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page4.php\">Medical<i class=\"fa fa-check-circle\"></i></a>";}  
			?>
		</div>
		<div class="nav-text">		    		    	
    		<?php
	    	if ($_SESSION['page5'] == 0 && $page != 5) {print "<div class=\"pageincomplete\">Additional</div>";}
			elseif ($page == 5) {print "<div class=\"pagecurrent\">Additional<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page5.php\">Experience<i class=\"fa fa-check-circle\"></i></a>";}  
			?>
		</div>		    	
    	<div class="nav-text">		    	
			<?php
	    	if ($_SESSION['page6'] == 0 && $page != 6) {print "<div class=\"pageincomplete\">Program</div>";}
			elseif ($page == 6) {print "<div class=\"pagecurrent\">Program<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page6.php\">Program<i class=\"fa fa-check-circle\"></i></a>";} 
			?>
		</div>		    	
		<div class="nav-text">		    	
	    	<?php
	    	if ($_SESSION['page7'] == 0 && $page != 7) {print "<div class=\"pageincomplete\">Session</div>";}
			elseif ($page == 7) {print "<div class=\"pagecurrent\">Session<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page7.php\">Session<i class=\"fa fa-check-circle\"></i></a>";} 
			?>
		</div>		
		<div class="nav-text">		    	
			<?php
	    	if ($_SESSION['page8'] == 0 && $page != 8) {print "<div class=\"pageincomplete\">Submit</div>";}
			elseif ($page == 8) {print "<div class=\"pagecurrent\">Submit<i class=\"fa fa-arrow-circle-right\"></i></div>";}
			else {print "<a class=\"pagecomplete\" href=\"page8.php\">Submit<i class=\"fa fa-check-circle\"></i></a>";} 
			?>
		</div>
		<div class="nav-text">Done!</div>
  	</nav>
<?php
}
?>
	