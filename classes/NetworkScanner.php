<?
/*
* ============================================================================
*  Name         : NetworkScanner.php
*  Part of      : WhoIsInTheLab
*  Description  : scan network and detect devices
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

require_once "DatabaseManager.php";

class NetworkScanner
{
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
	 * Scan the network and retrieve devices
	 *
	 * @return nothing
	 */
	public function run()
	{
		$sNetInfo = $this->scanNetwork();
		$devices = $this->parse($sNetInfo);
		$this->m_dbCtrl->saveOnlineDevices($devices);
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Get the IP of the default gateway
	 *
	 * @return string
	 */
	private function defaultGateway()
	{
		return trim(shell_exec("sudo route -n | grep 'UG[ \t]' | awk '{print $2}'"));
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Scans the network and returns raw IP, MAC and other info as text
	 *
	 * @return string
	 */
	private function scanNetwork()
	{
		$sCmd = "sudo arp-scan --interface=eth0 -l | egrep ";
		$sCmd .= "'[[:digit:]]{1,3}\.[[:digit:]]{1,3}\.[[:digit:]]{1,3}\.[[:digit:]]{1,3}'";
		return shell_exec($sCmd);
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Parse text to array with MAC as a key and IP as a value
	 *
	 * @return string
	 */
	private function parse($sTxt)
	{
		$lines = explode("\n", $sTxt);
		$devices = array();
		$sDefaultGateway = $this->defaultGateway();
		foreach ($lines as $sLine)
		{
			$deviceInfo = array();
			$nRes = preg_match("/^\s*((?:\d{1,3}\.){3}\d{1,3})\s+((?:[a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2})\s+(\S.*)/", $sLine, $deviceInfo);
			if (1 != $nRes)
			{
				continue;
			}
			$sIP = trim($deviceInfo[1]);
			if ($sIP == $sDefaultGateway)
			{
				continue;
			}
			$sMAC = trim($deviceInfo[2]);
			$devices[$sMAC] = $sIP;
		}
		return $devices;
	}
	//------------------------------------------------------------------------------
}
?>
