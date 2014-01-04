<?
/*
* ============================================================================
*  Name         : netScan.php
*  Part of      : WhoIsInTheLab
*  Description  : script that should be executed with root privileges from crontab
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once __DIR__."/../classes/NetworkScanner.php";

try 
{
	$netScanner = new NetworkScanner();
	$netScanner->run();
}
catch(Exception $ex)
{
	echo $ex->getMessage() ."\n";
}
?>
