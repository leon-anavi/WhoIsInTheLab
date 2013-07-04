<?
/*
* ============================================================================
*  Name         : User.php
*  Part of      : WhoIsInTheLab
*  Description  : User information
*  Author     	: Leon Anavi
*  Email		: leon@anavi.org
* ============================================================================
*/

class User
{
	private static $LINK_TWITTER = 'https://twitter.com/';

	private static $LINK_FACEBOOK = 'https://www.facebook.com/';

	private static $LINK_GOOGLEPLUS = 'https://plus.google.com/';

	private $m_sName1;
	
	private $m_sName2;
	
	private $m_sFacebook;
	
	private $m_sTwitter;

	private $m_sGooglePlus;
	
	private $m_sTel;

	private $m_sEmail;

	private $m_sWebsite;

	function __construct($sName1, $sName2, 
				$sFacebook, $sTwitter, $sGooglePlus,
				$sTel, $sEmail, $sWebsite)
	{
		$this->m_sName1 = $sName1;
		$this->m_sName2 = $sName2;
		$this->m_sFacebook = $sFacebook;
		$this->m_sTwitter = $sTwitter;
		$this->m_sGooglePlus = $sGooglePlus;
		$this->m_sTel = $sTel;
		$this->m_sEmail = $sEmail;
		$this->m_sWebsite = $sWebsite;
	}
	//------------------------------------------------------------------------------
	
	function __destruct()
	{
		//Nothing to do
	}
	//------------------------------------------------------------------------------
	
	public function __get($sName)
	{
		switch($sName)
		{
			case 'name':
				return $this->getName();
				
			case 'name1':
				return $this->m_sName1;
				
			case 'name2':
				return $this->m_sName2;

			case 'facebook':
				return $this->m_sFacebook;
				
			case 'facebookLink':
				return $this->getFacebookLink();
				
			case 'twitter':
				return $this->m_sTwitter;
				
			case 'twitterLink':
				return $this->getTwitterLink();

			case 'googlePlus':
				return $this->m_sGooglePlus;

			case 'googlePlusLink':
				return $this->getGooglePlusLink();
				
			case 'tel':
				return $this->m_sTel;

			case 'email':
				return $this->m_sEmail;
		
			case 'website':
				return $this->m_sWebsite;
					
			default:
				//unknown property
				return '';
		}
	}
	//------------------------------------------------------------------------------
	
	private function getName()
	{
		$sName = $this->m_sName1;
		if (false == empty($this->m_sName2))
		{
			$sName .= " {$this->m_sName2}";
		}
		return $sName;
	}
	//------------------------------------------------------------------------------
	
	private function getFacebookLink()
	{
		$sFbLink = '';
		if (false == empty($this->m_sFacebook))
		{
			$sFbLink = self::$LINK_FACEBOOK . $this->m_sFacebook;
		}
		return $sFbLink;
	}
	//------------------------------------------------------------------------------
	
	private function getTwitterLink()
	{
		$sTwitterLink = '';
		if (false == empty($this->m_sTwitter))
		{
			$sTwitterLink = self::$LINK_TWITTER . $this->m_sTwitter;
		}
		return $sTwitterLink;
	}
	//------------------------------------------------------------------------------

	private function getGooglePlusLink()
	{
		$sGooglePlusLink = '';
		if ( false == empty($this->m_sGooglePlus) )
		{
			$sGooglePlusLink = self::$LINK_GOOGLEPLUS . $this->m_sGooglePlus;
		}
		return $sGooglePlusLink;
	}
	//------------------------------------------------------------------------------

}
?>
