<?php
session_start();

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

?>
<div id="gl-main" class="pure-u-1"> 
	<div class="wrapper">
		<div id="infoarea" class="pure-g-r"> 
			<div class="pure-u-1">
				<?php
				$orderquery = "SELECT * FROM fd_orders_new ORDER BY order_id";
				foreach ($conn->query($orderquery) as $row)
				{
					//compare to food packs
					$stmt = $conn->prepare("SELECT * FROM fd_packs_new WHERE pack_id = :pack_id");
					$stmt->bindValue(':pack_id', $row['food_packs_id']);
					$stmt->execute();
					$pack = $stmt->fetchAll();
					
					//delete orphaned orders
					if (count($pack) == 0) 
					{
						print "Order Deleted: ".$row['order_id']."<br />";
						$stmt = $conn -> prepare("DELETE FROM fd_orders_new WHERE order_id = :order_id");   
					  $stmt -> bindValue(':order_id', $row['order_id']);	   
					  $stmt -> execute();
					}
						
					/*compare to items table
					$stmt = $conn->prepare("SELECT * FROM food_items_new
																	WHERE item_id = :item_id");
					$stmt->bindValue(':item_id', $row['food_items_id']);
					$stmt->execute();
					$item = $stmt->fetchAll();
						
					if (count($item) == 0)
					{
						print "Item Deleted: ".$row['order_id']."<br />";
						$stmt = $conn -> prepare("DELETE FROM food_orders_new WHERE order_id = :order_id");   
					  $stmt -> bindValue(':order_id', $row['order_id']);	   
					  $stmt -> execute();
					}*/
				}?>
		</div>
	</div>
</div>
