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
	
	private $m_nRefreshRate;
	
	/** 
	 * Constructor
	 * 
	 */
	public function __construct($sVersion='v2', $sLanguage='en', 
									$sApiVersion='20140104')
	{
		$cfgFile = parse_ini_file("/aux0/WhoIsInTheLab/db.cfg", true);
		$config = (isset($cfgFile['FOURSQUARE'])) ? $cfgFile['FOURSQUARE'] : array();
		
		$this->m_sVenueId = (isset($config['venue'])) ? $config['venue'] : false;
		$this->m_nRefreshRate = (isset($config['checkinPeriod'])) ? 
												$config['checkinPeriod'] : 24;
		
		$sClientId = (isset($config['key'])) ? $config['key'] : false;
		$sClientSecret = (isset($config['secret'])) ? $config['secret'] : false;
		$sRedirectUrl = (isset($config['url'])) ? $config['url'] : false; 
		
		parent::__construct($sClientId, $sClientSecret, $sRedirectUrl, 
							$sVersion, $sLanguage, $sApiVersion); 
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Get the refresh rate in hours
	 * 
	 * @return int refresh rate in hours
	 * @throws nothing
	 */
	public function getRefreshRate()
	{
			return $this->m_nRefreshRate;
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
	
	/**
	 * Get access token 
	 * 
	 * @param sCode code
	 * 
	 * @return string access token
	 * @throws nothing
	 */
	public function getAccessToken($sCode)
	{
		return $this->GetToken($sCode, $this->RedirectUri);
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Get authentication link with the configured redirect URL
	 * 
	 * @return string the authentication link
	 * @throws nothing
	 */
	public function getAuthenticationLink()
	{
		return $this->AuthenticationLink($this->RedirectUri);
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Get full name of the user
	 * 
	 * @param $sAcessToken access token of the user
	 * 
	 * @return string name
	 * @throws nothing
	 */
	public function getName($sAccessToken)
	{
		//get user name
		$this->SetAccessToken($sAccessToken);
		//show current user
		$response = $this->GetPrivate("users/self");
		$res = json_decode($response);
		$sUserFullname = '';
		if (true == isset($res->response->user))
		{
			if (true == property_exists($res->response->user, "firstName")) 
			{
				$sUserFullname = $res->response->user->firstName;
			}
			
			if (true == property_exists($res->response->user, "lastName")) 
			{
				if (false == empty($sUserFullname ))
				{
					$sUserFullname .= ' ';
				}
				$sUserFullname .= $res->response->user->lastName;
			}
		}
		return $sUserFullname;
	}
	//------------------------------------------------------------------------------
}

