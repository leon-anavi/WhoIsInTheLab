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
require_once "User.php";

class NetworkObserver
{

	private static $LINK_TWITTER = 'https://twitter.com/';
	private static $LINK_FACEBOOK = 'https://www.facebook.com/';

	private $m_dbCtrl;

	private $m_nDevicesCount;

	private $m_nGuests;

	private $m_users;

	function __construct()
	{
		$this->m_dbCtrl = new DatabaseManager();
		$this->m_nDevicesCount = 0;
		$this->m_nGuests = 0;
		$this->m_users = array();
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
		//make sure that the argument will be process correctly
		$sType = strtoupper($sType);
		
		$devices = $this->m_dbCtrl->listOnlineDevices();
		$this->m_nDevicesCount = count($devices);
		$this->extractUsers($devices);
	
		switch($sType)
		{
			case 'JSON':
				echo $this->listJSON();
			break;
			
			case 'HTML':
				echo $this->listHTML();
			break;
			
			case 'XML':
				echo $this->listXML();
			break;
			
			case 'TXT':
			default:
				//plain text
				echo $this->listPlainText();
			break;
		}
	}
	//------------------------------------------------------------------------------
	
	private function extractUsers($devices)
	{
		$users = array();
		$nGuests = 0; 
		foreach($devices as $device)
		{
			if ( (false == empty($device['user_name1'])) ||
				 (false == empty($device['user_name2'])) ||
				 (false == empty($device['user_twitter'])) ||
				 (false == empty($device['user_facebook'])) ||
				 (false == empty($device['user_tel'])) || 
				 (false == empty($device['user_email'])) )
			{
				$users[] = new User($device['user_name1'], $device['user_name2'], 
						$device['user_facebook'], $device['user_twitter'],
						$device['user_tel'], $device['user_email']);
			}
			else
			{
				//all unknown devices are marked as guests
				$nGuests += 1;
			}
		}
		$this->m_nGuests = $nGuests;
		$this->m_users = $users;
	}
	//------------------------------------------------------------------------------
	
	private function listJSON()
	{
		$output = array();
		//error status
		$output['error'] = array('ErrCode' => 0, 'ErrMsg' => '');
		//prepare users
		$jsonUsers = array();
		foreach($this->m_users as $user)
		{
			$jsonUser = array('name1' => $user->name1, 
					'name2' => $user->name2,
					'twitter' => $user->twitterLink,
					'facebook' => $user->facebookLink,
					'tel' => $user->tel,
					'email' => $user->email);
			array_push($jsonUsers, $jsonUser);
		}
		//append the total count nad the users to the data
		$output['data'] = array('count' => $this->m_nDevicesCount,
					'guests' => $this->m_nGuests, 
					'users' => $jsonUsers );
		return json_encode($output);
	}
	//------------------------------------------------------------------------------
	
	private function listHTML()
	{
		$sOutput = "<h2>Online Devices: {$this->m_nDevicesCount}</h2>\n";
		$sOutput .= "<h2>Guests: {$this->m_nGuests}</h2>\n";
		$sOutput .= "<ul>\n";
		foreach($this->m_users as $user)
		{
			$sOutput .= "<li>";
			$sOutput .= $user->name;
			$sTwitter = $user->twitter;
			if (false == empty($sTwitter))
			{
				$sOutput .= " twitter: <a href =\"{$user->twitterLink}\">{$sTwitter}</a>";
			}
			$sFb = $user->facebook;
			if (false == empty($sFb))
			{
				$sOutput .= " facebook: <a href=\"{$user->facebookLink}\">{$sFb}</a>";
			}
			$sTel = $user->tel;
			if (false == empty($sTel))
			{
				$sOutput .= " tel: <a href=\"callto:{$sTel}\">{$sTel}</a>";
			}
			echo "</li>\n";
			$sEmail = $user->email;
			if (false == empty($sEmail))
			{
				$sOutput .= " email: <a href=\"mailto:{$sEmail}\">{$sEmail}</a>";
			}
		}
		$sOutput .= "</ul>\n";
		return $sOutput;
	}
	//------------------------------------------------------------------------------
	
	private function listXML()
	{
		$sOutPut = '';
		try
		{
			$xml = new DOMDocument("1.0");
			//root
			$root = $xml->createElement('who');
			$xml->appendChild($root);
			//error
			$error = $xml->createElement('error');
			$root->appendChild($error);
			//error code
			$ErrCode = $xml->createElement('ErrCode');
			$ErrCodeText = $xml->createTextNode('0');
			$ErrCode->appendChild($ErrCodeText);
			$error->appendChild($ErrCode);
			//error message
			$ErrMsg = $xml->createElement('ErrMsg');
			$ErrMsgText = $xml->createTextNode('');
			$ErrMsg->appendChild($ErrMsgText);
			$error->appendChild($ErrMsg);
			//data
			$data = $xml->createElement('data');
			$root->appendChild($data);
			//total number of devices
			$count = $xml->createElement('count');
			$countText = $xml->createTextNode($this->m_nDevicesCount);
			$count->appendChild($countText);
			$data->appendChild($count);
			//guests
			$guests = $xml->createElement('guests');
			$guestsText = $xml->createTextNode($this->m_nGuests);
			$guests->appendChild($guestsText);
			$data->appendChild($guests);
			//users
			$xmlUsers = $xml->createElement('users');
			$data->appendChild($xmlUsers);
			//user
			foreach($this->m_users as $user)
			{
				$xmlUser = $xml->createElement('user');
				//name1
				$xmlName1 = $xml->createAttribute('name1');
				$xmlName1->value = $user->name1;
				$xmlUser->appendChild($xmlName1);
				//name2
				$xmlName2 = $xml->createAttribute('name2');
				$xmlName2->value = $user->name2;
				$xmlUser->appendChild($xmlName2);
				//facebook
				$xmlFb = $xml->createAttribute('facebook');
				$xmlFb->value = $user->facebook;
				$xmlUser->appendChild($xmlFb);
				//twitter
				$xmlTwitter = $xml->createAttribute('twitter');
				$xmlTwitter->value = $user->twitter;
				$xmlUser->appendChild($xmlTwitter);
				//tel
				$xmlTel = $xml->createAttribute('tel');
				$xmlTel->value = $user->tel;
				$xmlUser->appendChild($xmlTel);
				//email
				$xmlEmail = $xml->createAttribute('email');
				$xmlEmail->value = $user->email;
				$xmlUser->appendChild($xmlEmail);
				
				$xmlUsers->appendChild($xmlUser);
			}
			
			$xml->preserveWhiteSpace = false;
			$xml->formatOutput = true;
			$sOutPut = $xml->saveXML();
		}
		catch (Exception $ex)
		{
			//Nothing to do
			print_r($ex);
		}
		return $sOutPut;
	}
	//------------------------------------------------------------------------------
	
	private function listPlainText()
	{
		$sOutput = "Online Devices: {$this->m_nDevicesCount} \n";
		$sOutput .= "Guests: {$this->m_nGuests} \n";
		foreach($this->m_users as $user)
		{
			$sOutput .= "Name: {$user->name} ";
			$sTwitter = $user->twitterLink;
			if (false == empty($sTwitter))
			{
				$sOutput .= "Twitter: {$sTwitter} ";
			}
			$sFb = $user->facebookLink;
			if (false == empty($sFb))
			{
				$sOutput .= "Facebook: {$sFb} ";
			}
			$sTel = $user->tel;
			if (false == empty($sTel))
			{
				$sOutput .= "tel: {$sTel} ";
			}
			$sEmail = $user->email;
			if (false == empty($sEmail))
			{
				$sOutput .= "email: {$sEmail} ";
			}
			$sOutput .= "\n";
		}
		return $sOutput;
	}
	//------------------------------------------------------------------------------
		
}
?>
