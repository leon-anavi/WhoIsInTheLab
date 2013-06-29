<?
/*
* ============================================================================
*  Name         : api.php
*  Part of      : WhoIsInTheLab
*  Description  : API to list user and devices
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once __DIR__."/../classes/NetworkObserver.php";

$sFormat = (isset($_REQUEST['format'])) ? $_REQUEST['format'] : 'TXT';

$observer = new NetworkObserver();
$observer->listOnlineUsers($sFormat);
?>
