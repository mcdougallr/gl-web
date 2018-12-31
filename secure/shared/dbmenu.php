<nav id="db-menu" class="nav-menu left-menu reg">
	<ul>
		<?php 
		// STAFF
		if ($_SESSION['refer'] != "staff")
		{?>
	        <li style="background-color: #39F;">
	        	<a href="../staff/index.php" class="gl-db-menu-item"><i class="fa fa-user"></i><?php print $staffaccess['staff_name_common'];?>'s Staff Page</a>
	        </li>
		<?php 
		}
		
		// STAFF ADMIN
	    if ($staffaccess['staff_access'] > 3 AND $_SESSION['refer'] != "sa")
		{ ?>
	        <li style="background-color: #6F9FC4;">
	        	<a href="../staff-admin/index.php" class="gl-db-menu-item"><i class="fa fa-users"></i>Staff Admin</a>
	        	</li>      	
	    <?php
		}
		
		// STUDENT ADMIN
		if ($staffaccess['staff_access'] > 2 AND $_SESSION['refer'] != "sta")
		{ ?>
	        <li style="background-color: #FF8033;">
	        	<a href="../student-admin/index.php" class="gl-db-menu-item"><i class="fa fa-bug"></i>Student Admin</a>
	        </li>
	   	<?php
		}
		
		// SESSION ADMIN
		if ($staffaccess['staff_access'] > 2 AND $_SESSION['refer'] != "ses")
		{ ?>
	        <li style="background-color: #685191;">
	        	<a href="../session-admin/index.php" class="gl-db-menu-item"><i class="fa fa-picture-o"></i>Session Admin</a>
	        </li>
	   	<?php
		}
		
		// SCHOOL YEAR ADMIN
	    if ($staffaccess['staff_access'] > 2 AND $_SESSION['refer'] != "sya")
		{ ?>  
			<li style="background-color: #A82B2B;">
				<a href="../school-year-admin/index.php" class="gl-db-menu-item"><i class="fa fa-leaf"></i>School Year Admin</a>
			</li>
		<?php
		}
		
		// SCHOOL YEAR
	    if ($_SESSION['refer'] != "sy")
		{ ?>  
			<li style="background-color: #C33;">
				<a href="../school-year/index.php" class="gl-db-menu-item"><i class="fa fa-tree"></i>School Year</a>
			</li>
		<?php
		}
		
		// FOOD
	    if ($_SESSION['refer'] != "food")
		{ ?> 
	        <li style="background-color: #76BC83;">
	        	<a href="../food/index.php" class="gl-db-menu-item"><i class="fa fa-spoon"></i>Food</a>
	        </li>
      	<?php
		}
		
		// EQ
	    if ($_SESSION['refer'] != "eq")
		{ ?> 
	        <li style="background-color: #999999;">
	        	<a href="../eq/index.php" class="gl-db-menu-item"><i class="fa fa-wrench"></i>Equipment</a>
	        </li>
        <?php
		}
		
		// LOGISTICS
	    if ($_SESSION['refer'] != "log")
		{ ?> 
	        <li style="background-color: #A4A48E;">
	        	<a href="../logistics/index.php" class="gl-db-menu-item"><i class="fa fa-cog"></i>Logistics</a>
        	</li>			
        <?php
		}
		?> 			
	</ul>
	<div style="height: 50px;"></div>
</nav>

<script>
	$(document).ready(function() {

		$(document).click(function(event) {
			if (!$(event.target).closest('#db-menu').length) {
				if ($('#db-menu').hasClass("active")) {
					$('#db-menu').removeClass("active");
					$('#db-menu').animate({
						left : '-277px'
					}, 300);
				}
			}
		});

	}); 
</script>
