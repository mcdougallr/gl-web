<?php
session_start();
$_SESSION['date'] = date('Y-m-d', strtotime('-1 month', strtotime($_SESSION['date'])));