<?
/*
* ============================================================================
*  Name         : DatabaseManager.php
*  Part of      : WhoIsInTheLab
*  Description  : database manager
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

class DatabaseManager
{
	private $m_db;
	
	private static $DB_BLACKLIST = 'who_blacklist';
	private static $DB_DEVICES = 'who_devices';
	private static $DB_ONLINE = 'who_online';
	private static $DB_USERS = 'who_users';

	function __construct()
	{
		$this->m_db = new mysqli('localhost', 'root', 'leon123', 'whoIsInTheLab');
		if ($this->m_db->connect_error) 
		{
			die('Connect Error: ' . $this->m_db->connect_error);
		}
	}
	//------------------------------------------------------------------------------
	
	function __destruct()
	{
		//Nothing to do
	}
	//------------------------------------------------------------------------------
	
	public function saveOnlineDevices($devices)
	{
		//remove all existing records
		$this->removeAllOnlineDevices();
		
		$sSQL = "INSERT INTO ".self::$DB_ONLINE." ( ";
		$sSQL .= "online_MAC, online_IP) VALUES ";
		$bIsFirst = true;
		foreach ($devices as $sMAC => $sIP)
		{
			if (false == $bIsFirst)
			{
				$sSQL .= ", ";
			}
			else
			{
				$bIsFirst = false;
			}
			$sSQL .= "('".addslashes($sMAC)."',  '".addslashes($sIP)."')";
		}
		$this->m_db->query($sSQL);
	}
	//------------------------------------------------------------------------------
	
	public function listOnlineDevices()
	{
		$sSQL = "SELECT ";
		$sSQL .= "IFNULL(user_id,UUID()) as user, ";
		$sSQL .= "user_name1, user_name2, ";
		$sSQL .= "user_twitter, user_facebook ";
		$sSQL .= "FROM ".self::$DB_ONLINE;
		$sSQL .= " LEFT JOIN ".self::$DB_DEVICES." ON online_MAC = device_MAC ";
		$sSQL .= "LEFT JOIN ".self::$DB_USERS." ON device_uid = user_id ";
		$sSQL .= "WHERE online_MAC NOT IN (SELECT blacklist_MAC FROM ".self::$DB_BLACKLIST.") ";
		$sSQL .= "GROUP BY user ";
		$sSQL .= "ORDER BY user_name1 ASC ";
		$res = $this->m_db->query($sSQL);
		$devices = array();
		while ($row = $res->fetch_assoc())
		{
			$devices[] = $row;
		}
		return $devices;
	}
	//------------------------------------------------------------------------------
	
	private function removeAllOnlineDevices()
	{
		$sSQL = 'TRUNCATE TABLE '.self::$DB_ONLINE;
		$this->m_db->query($sSQL);
	}
	//------------------------------------------------------------------------------
}
?>
