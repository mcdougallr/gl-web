<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../phpshared/css.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Order All Totals</title>

<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/functions.php');
include ('../shared/authenticate.php');

$supplier_id = $_GET['sid'];

?>

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>

</head>

<body>
	
    <?php
		$qs = "select * from fd_suppliers_new where supplier_id = '$supplier_id'";
		$rs = mysql_query($qs);
		$ps = mysql_fetch_assoc($rs);
		$course_list = "";
		?>
			<table width=100% border=1 cellspacing=0 cellpadding=0>
				<tr>
					<td><img src="img/print_sunman.jpg" width=100 /></td>
					<td class="verdana24 b" align=center>Gould Lake Outdoor Centre</td>
        		</tr><tr height=40>
					<td colspan=2 class="verdana24 b" align=center><?php print $ps['supplier_name']; ?> Order</td>
        		</tr><tr>
                	<td colspan=2 align=center><br />
                        <table width=700 border=1>
                        	<?php
    
                            $qb = "select * from fd_items_new 
                                    left join fd_suppliers_new on fd_items_new.food_suppliers_id = fd_suppliers_new.supplier_id
                                    where supplier_id = '$supplier_id'
                                    order by item_name";
                            $rb = mysql_query($qb);
                            while ($pb = mysql_fetch_assoc($rb))
                            {
                                print "<tr><td width=200 align=left class=\"verdana11\">".$pb['item_name']."</td><td width=100 align=center class=\"verdana11\">";
                                $item_id = $pb['item_id'];
                                $amount_total = 0;
                                $course_list = "";
                                $qz = "select * from fd_orders_new
                                        left join fd_packs_new on fd_packs_new.pack_id = fd_orders_new.food_packs_id
                                        where food_items_id = '$item_id'";
                                $rz = mysql_query($qz);
                                while ($pz = mysql_fetch_assoc($rz))
                                {
                                    if ($pz['order_amount'] != 0)
                                    {
                                        $course_list =  $course_list.$pz['pack_session_name']." (".$pz['pack_session_portion'].") - ".$pz['pack_groups_in_session']." Food Groups @ ".$pz['order_amount']." ".$pb['item_unit']."<br />";
                                        $amount_total = $amount_total + ($pz['order_amount']*$pz['pack_groups_in_session']);
                                    }
                                    
                                }
							if ($pb['item_unit'] == "g")
								{print ($amount_total/1000)." kg</td>";}
							elseif ($pb['item_unit'] == "ml")
								{print ($amount_total/1000)." L</td>";}
							else
								{print $amount_total." ".$pb['item_unit']."</td>";}
							print "<td width=400 align=left class=\"verdana10\">".$course_list."</tr>";
                            }
							?>
							</table><br />
                    	</td>
              		</tr>
				</td>
			</tr>
		</table>
</body>
</html>
