<?
/*
* ============================================================================
*  Name         : netList.php
*  Part of      : WhoIsInTheLab
*  Description  : list user and devices
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once __DIR__."/../classes/NetworkObserver.php";

$observer = new NetworkObserver();
$observer->listOnlineUsers('plain');
?>
