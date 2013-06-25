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

require_once __DIR__."/../classes/NetworkScanner.php";

$netScanner = new NetworkScanner();
$netScanner->run();
?>
