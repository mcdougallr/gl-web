<?php

	include ('../shared/dbconnect.php');
	
	$search_term = "%".strip_tags($_POST['searchterm'])."%";
	
	$stmt = $conn -> prepare("SELECT item_id, item_name FROM fd_items WHERE item_name LIKE :item_name ORDER BY item_name");
	$stmt -> bindValue(':item_name', $search_term);
	$stmt -> execute();
	$itemlist = $stmt->fetchAll();
	
	if ($itemlist != NULL) 
	{
		foreach ($itemlist as $item)
		{
			print "<li><a href=\"edit-item.php?iid=";
		  	print $item['item_id']."\" class=\"gl-menu-item main-m search\"><i class=\"fa fa-caret-right\"></i>";
		  	print $item['item_name']."</a></li>";
		}
	}
	else {print "<li style=\"color: #FFF;font-weight: 300;\"><i class=\"fa fa-frown-o\"></i>No items meet that criteria...</li>";}
			    	
?>