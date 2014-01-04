<?php
/*
* ============================================================================
*  Name         : FourSquareManager.php
*  Part of      : WhoIsInTheLab
*  Description  : Manage FourSquare operations
*  Author       : Leon Anavi
*  Email        : leon@anavi.org
* ============================================================================
*/

require_once "FoursquareAPI.class.php";

class FourSquareManager extends FoursquareApi
{
	private $m_sVenueId; 
	
	/** 
	 * Constructor
	 * 
	 */
	public function __construct($sVersion='v2', $sLanguage='en', 
									$sApiVersion='20140104')
	{
		$cfgFile = parse_ini_file("/aux0/WhoIsInTheLab/db.cfg", true);
		$config = $cfgFile['FOURSQUARE'];
		
		$this->m_sVenueId = (isset($config['venue'])) ? $config['venue'] : false;
		
		$sClientId = (isset($config['key'])) ? $config['key'] : false;
		$sClientSecret = (isset($config['secret'])) ? $config['secret'] : false;
		$sRedirectUrl = (isset($config['url'])) ? $config['url'] : false; 
		
		parent::__construct($sClientId, $sClientSecret, $sRedirectUrl, 
							$sVersion, $sLanguage, $sApiVersion); 
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Check into the configured venue
	 * 
	 * @param $sAcessToken access token of the user
	 * 
	 * @return nothing
	 * @throws nothing
	 */
	public function checkIn($sAcessToken)
	{
		$this->SetAccessToken($sAcessToken);
		$params = array('venueId'=>$this->m_sVenueId);
        $this->GetPrivate("checkins/add",$params,true);
	}
	//------------------------------------------------------------------------------
}

