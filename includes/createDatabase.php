<?php
//This file is included in all files which require a database connection.  It checks for a connection and redirects to an error page in the case that one can't be established
require_once("meta.php");
require_once("Log.php");
$db = new Database();
if(!$res = $db->connect()) {
	$log = new Log(((__DIR__)). "\..\logs\log.txt");
	$log->writeLog($db->getError());
	header("Location: " . HOST . "/error.php?errorCode=0");
}
?>