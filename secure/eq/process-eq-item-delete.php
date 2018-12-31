<?php

include ('../shared/dbconnect.php');
include ('../shared/clean.php');

$id = cleantext($_POST['id']);

$stmt = $conn -> prepare("DELETE FROM eq_items WHERE item_id= :item_id");
$stmt -> bindValue(':item_id', $id);
$stmt -> execute();

?>
