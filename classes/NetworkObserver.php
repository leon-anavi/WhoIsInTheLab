<?
/*
* ============================================================================
*  Name         : NetworkObserver.php
*  Part of      : WhoIsInTheLab
*  Description  : scan network and detect devices
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

require_once "DatabaseManager.php";

class NetworkObserver
{

	private static $LINK_TWITTER = 'https://twitter.com/';
	private static $LINK_FACEBOOK = 'https://www.facebook.com/';

	private $m_dbCtrl;

	function __construct()
	{
		$this->m_dbCtrl = new DatabaseManager();
	}
	//------------------------------------------------------------------------------
	
	function __destruct()
	{
		//Nothing to do
	}
	//------------------------------------------------------------------------------
	
	/**
	 * List users and number of active devices
	 *
	 * @return nothing
	 */
	public function listOnlineUsers($sType)
	{
		$devices = $this->m_dbCtrl->listOnlineDevices();
		$nDevicesCount = count($devices);
		$users = $this->extractUsers($devices);
	
		switch($sType)
		{
			case 'JSON':
				echo $this->listJSON($nDevicesCount, $users);
			break;
			
			case 'HTML':
				echo $this->listHTML($nDevicesCount, $users);
			break;
			
			case 'XML':
				echo $this->listXML($nDevicesCount, $users);
			break;
			
			default:
				//plain text
				echo $this->listPlainText($nDevicesCount, $users);
			break;
		}
	}
	//------------------------------------------------------------------------------
	
	private function extractUsers($devices)
	{
		$users = array();
		foreach($devices as $device)
		{
			if ( (true == isset($device['user_name1'])) ||
				 (false == empty($device['user_name2'])) ||
				 (false == empty($device['twitter'])) ||
				 (false == empty($device['facebook'])) )
			{
				$users[] = $device;
			}
		}
		return $users;
	}
	//------------------------------------------------------------------------------
	
	private function listJSON($nDevicesCount, $users)
	{
	
	}
	//------------------------------------------------------------------------------
	
	private function listHTML($nDevicesCount, $users)
	{
	
	}
	//------------------------------------------------------------------------------
	
	private function listXML($nDevicesCount, $users)
	{
	}
	//------------------------------------------------------------------------------
	
	private function listPlainText($nDevicesCount, $users)
	{
		echo "Online: {$nDevicesCount} \n";
		foreach($users as $user)
		{
			echo "Name: {$user['user_name1']} {$user['user_name2']} ";
			if (false == empty($user['user_twitter']))
			{
				echo "Twitter: ".self::$LINK_TWITTER."{$user['user_twitter']} ";
			} 
			if (false == empty($user['user_facebook']))
			{
				echo "Facebook: ".self::$LINK_FACEBOOK."{$user['user_facebook']}";
			}
			echo "\n";
		}
	}
	//------------------------------------------------------------------------------
		
}
?>
