<?
/*
* ============================================================================
*  Name         : DatabaseManager.php
*  Part of      : WhoIsInTheLab
*  Description  : database manager
*  Author     	: Leon Anavi
*  Email	: leon@anavi.org
* ============================================================================
*/

class DatabaseManager
{
	private $m_db;
	
	private static $DB_BLACKLIST = 'who_blacklist';
	private static $DB_DEVICES = 'who_devices';
	private static $DB_ONLINE = 'who_online';
	private static $DB_USERS = 'who_users';
	private static $DB_HISTORY = 'who_history';

	function __construct()
	{
		$cfgFile = parse_ini_file("/aux0/WhoIsInTheLab/db.cfg", true);
		$config = $cfgFile['MYSQL'];

		$this->m_db = new mysqli($config['host'], $config['username'], 
					 $config['password'], $config['database']);
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
	    $sTable = self::$DB_ONLINE;
	
	    $sSQL = <<<ESQL
	        SELECT online_id, online_MAC, online_since
	        FROM $sTable
ESQL;
	    $res = $this->m_db->query($sSQL);
	    
	    $existingDevices = array();
	    while ($row = $res->fetch_assoc())
		{
			$existingDevices[$row['online_MAC']] = array(
			    'id' => $row['online_id'],
			    'since' => $row['online_since']);
		}
		
		$oldDevices = array_diff_key($existingDevices, $devices);
		$newDevices = array_diff_key($devices, $existingDevices);

        // Insert the new devices we found
        if (count($newDevices)) {
		    $sSQL = "INSERT INTO $sTable ( ";
		    $sSQL .= "online_MAC, online_IP) VALUES ";
		    $bIsFirst = true;
		    foreach ($newDevices as $sMAC => $sIP)
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

        // Delete the devices who are no longer online
        if (count($oldDevices)) {
		    $sSQL = "DELETE FROM $sTable WHERE online_id in (";
		    $bIsFirst = true;
		    foreach ($oldDevices as $def) {
		        if ($bIsFirst) { 
		            $bIsFirst = false;
		        } else {
		            $sSQL .= ', ';
		        }
		        $sSQL .= "'" . addslashes($def['id']) . "'";
		    }
		    $sSQL .= ");";
		    $this->m_db->query($sSQL);
		}


        // Delete the devices who are no longer online
        if (count($oldDevices)) {
		    $sSQL = "INSERT INTO " . self::$DB_HISTORY . " ( history_MAC, history_from ) VALUES ";
		    $bIsFirst = true;
		    foreach ($oldDevices as $sMAC => $def) {
		        if ($bIsFirst) { 
		            $bIsFirst = false;
		        } else {
		            $sSQL .= ', ';
		        }
			    $sSQL .= "('".addslashes($sMAC)."', '".addslashes($def['since'])."')";
		    }
		    $this->m_db->query($sSQL);
		}
	}
	//------------------------------------------------------------------------------
	
	public function listOnlineDevices()
	{
		$sSQL = "SELECT ";
		$sSQL .= "IFNULL(user_id,UUID()) as user, ";
		$sSQL .= "user_name1, user_name2, ";
		$sSQL .= "user_twitter, user_facebook, user_google_plus, ";
		$sSQL .= "user_tel, user_email, user_website ";
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
