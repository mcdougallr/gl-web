<?php
session_start();

include ('../shared/functions.php');

$logout = logout();

header("Location: index.php");
?>
