<?php
/*
|---------------------------------------------------------------
| CASTING argc AND argv INTO LOCAL VARIABLES
|---------------------------------------------------------------
|
*/
$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];
 
// INTERPRETTING INPUT
if ($argc > 1 && isset($argv[1])) {
	$_SERVER['PATH_INFO']   = $argv[1];
	$_SERVER['REQUEST_URI'] = $argv[1];

	set_time_limit(0);
	require_once('index.php');
} 
