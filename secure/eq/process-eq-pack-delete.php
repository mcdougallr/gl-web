<?php

include ('../shared/dbconnect.php');
include ('../shared/functions.php');

$id = cleantext($_POST['id']);

$stmt = $conn -> prepare("DELETE FROM eq_packs WHERE pack_id= :pack_id");
$stmt -> bindValue(':pack_id', $id);
$stmt -> execute();

$stmt = $conn -> prepare("DELETE FROM eq_pack_items WHERE pack_item_pack_id= :pack_id");
$stmt -> bindValue(':pack_id', $id);
$stmt -> execute();

?>
