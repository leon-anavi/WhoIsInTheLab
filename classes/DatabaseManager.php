<?php
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

	private	static	$USER_ONLINE_TIME		= 20;	//	minutes
	
	function __construct()
	{
		$cfgFile = parse_ini_file("../config/db.cfg", true);
		$config = $cfgFile['MYSQL'];

		//	Get the number of minutes to consider a user to be online.
		//	If we detect a user to be online now, we will consider him
		//	online for this amount of minutes.
		if( isset($config['userOnlineTime']) )
			self::$USER_ONLINE_TIME	= $config['userOnlineTime'];

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


	/**
	 * Save online device to database
	 * 
	 * @param $devices array with online devices
	 * 
	 * @return nothing
	 * @throws Exception on error
	 */
	public function saveOnlineDevices($devices)
	{
		//	Do these steps
		//	1. Insert new devices in the Database
		//	2. Update old devices that are in $devices
		//	3. Delete devices that expired

		
		
		//	1. Insert new devices in the Database
		//	2. Update old devices that are in $devices
		$this->insertUpdateDevices( $devices );


		

		//	If we detect a user to be online now, we will consider him
		//	online for this amount of minutes - self::$USER_ONLINE_TIME.
		$sOldest	= time() - (self::$USER_ONLINE_TIME * 60);
		
		//	When we detect a time has elapsed for a user,
		//	we move its record to the history table.

		//	Copy the old devices to the history table
		$sSQL	= "INSERT INTO " . self::$DB_HISTORY . "(history_MAC, history_from)";
		$sSQL	.= " SELECT online_MAC, online_since FROM " . self::$DB_ONLINE;
		$sSQL	.= " WHERE online_last<FROM_UNIXTIME(" . $sOldest . ")";
		$this->m_db->query($sSQL);

		//	Remove the old devices from the online table
		$sSQL	= "DELETE FROM " . self::$DB_ONLINE;
		$sSQL	.= " WHERE online_last<FROM_UNIXTIME(" . $sOldest . ")";
		$this->m_db->query($sSQL);
	}
	//------------------------------------------------------------------------------
	

	/**
	 * Save online device to database.
	 * Update devices that are already in the database.
	 * 
	 * @param $devices array with online devices
	 * 
	 * @return nothing
	 * @throws Exception on error
	 */
	private	function	insertUpdateDevices( $devices )
	{
	    $sSQL = "SELECT online_id, online_MAC, online_since FROM " . self::$DB_ONLINE;
	        
	    $res = $this->m_db->query($sSQL);
	    if( false == $res )
	    {
			throw new Exception("Error: {$this->m_db->error}\n");
		}
	    
	    $existingDevices = array();
	    while ($row = $res->fetch_assoc())
		{
			$existingDevices[$row['online_MAC']] = array(
			    'id' => $row['online_id'],
			    'since' => $row['online_since']);
		}
		

        //	Insert the new devices we found
		$newDevices = array_diff_key( $devices, $existingDevices );		//	New devices (not in DB)
        if( count($newDevices) )
		{
		    $sSQL = "INSERT INTO " . self::$DB_ONLINE . "(online_MAC, online_IP) VALUES";
		    $bIsFirst = true;
		    foreach( $newDevices as $sMAC => $sIP )
		    {
			    if( false == $bIsFirst )
			    {
				    $sSQL .= ",";
			    }
			    else
			    {
				    $bIsFirst = false;
			    }
			    $sSQL .= "('".addslashes($sMAC)."','".addslashes($sIP)."')";
		    }
		    $this->m_db->query($sSQL);
		}

        //	Update existing devices
		$oldDevices = array_intersect_key( $existingDevices, $devices );	//	Devices already in the DB
		$timeNow	= time();
        if( count($oldDevices) )
		{
		    $sSQL = "UPDATE " . self::$DB_ONLINE;
		    $sSQL .= " SET online_last=FROM_UNIXTIME(" . $timeNow . ") WHERE ";
		    $bIsFirst = true;
		    foreach( $oldDevices as $sMAC => $row )
		    {
				$id	= $row["id"];
			    if( false == $bIsFirst )
			    {
				    $sSQL .= " OR ";
			    }
			    else
			    {
				    $bIsFirst = false;
			    }
			    $sSQL .= "online_id=".addslashes($id);
		    }
		    $res	= $this->m_db->query($sSQL);
		}
	}
	//------------------------------------------------------------------------------
	
	
	/**
	 * update the last checkin date
	 * 
	 * @param $users array with keys that must match the ids of the users
	 * 
	 * @return nothing
	 * @throws nothing
	 */
	public function updateFourSquareLastCheckin($users)
	{
		if (0 == count($users))
		{
			//no need to update anything
			return;
		}
		$sSQL = "UPDATE ".self::$DB_USERS." SET user_fscheckin = NOW() WHERE ";
		$bIsFirst = true;
		foreach($users as $nUserId => $sToken)
		{
			if (true == $bIsFirst)
			{
				$bIsFirst = false;
			}
			else
			{
				$sSQL .= " OR ";
			}
			$sSQL .= "user_id={$nUserId}";
		}
		$res = $this->m_db->query($sSQL);	
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Get acess token of Foursquare users
	 * 
	 * @param $nMinPeriod minimal perion in hours between checkins
	 * 
	 * @return array
	 * @throws nothing
	 */
	public function getFourSquareTokens($nMinPeriod = 24)
	{
		$tokens = array();
		$sSQL = "SELECT user_id, user_fstoken ";
		$sSQL .= "FROM ".self::$DB_ONLINE;
		$sSQL .= " LEFT JOIN ".self::$DB_DEVICES." ON online_MAC = device_MAC ";
		$sSQL .= "LEFT JOIN ".self::$DB_USERS." ON device_uid = user_id ";
		$sSQL .= "WHERE online_MAC NOT IN (SELECT blacklist_MAC FROM ".self::$DB_BLACKLIST.") ";
		$sSQL .= "AND user_fstoken <> '' ";
		$sSQL .= "AND (UNIX_TIMESTAMP(user_fscheckin) + $nMinPeriod *3600) < UNIX_TIMESTAMP() ";
		$sSQL .= "GROUP BY user_fstoken ";
		$res = $this->m_db->query($sSQL);
		if (false === $res)
		{
			return $tokens;
		}
		
		while ($row = $res->fetch_assoc())
		{
			$tokens[$row['user_id']] = $row['user_fstoken'];
		}
		return $tokens;
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
