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

	private $m_dbCtrl;

	private $m_nDevicesCount;

	private $m_nGuests;

	private $m_users;
	
	const GUEST = 'guest';

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
	public function listOnlineUsers($sType, $bCallback = false)
	{
		//make sure that the argument will be process correctly
		$sType = strtoupper($sType);
		
		$devices = $this->m_dbCtrl->listOnlineDevices();
		$this->m_nDevicesCount = count($devices);
		$this->extractUsers($devices);
	
		switch($sType)
		{
			case 'JSON':
			    $sContentType = 'application/json';
				$response = $this->listJSON();
			break;
			
			case 'HTML':
			    $sContentType = 'text/html';
				$response = $this->listHTML();
			break;
			
			case 'XML':
			    $sContentType = 'application/xml';
				$response = $this->listXML();
			break;
			
			case 'USERS':
				echo $this->listUsers();
			break;
			
			case 'TXT':
			default:
				//plain text
			    $sContentType = 'text/plain';
				$response = $this->listPlainText();
			break;
		}
		
		if (true == $bCallback) 
		{
			if ('JSON' != $sType)
			{
				$data = array(strtolower($sType) => $response);
			}
			else
			{
				$data = json_decode($response);
			}
		    
		    header('Content-Type', 'text/javascript');
		    echo $callback . '(';
		    echo json_encode($data, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT);
		    echo ')';
		}
		else 
		{
			//just output the response
			header('Content-Type', $sContentType);
			echo $response;
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
				 (false == empty($device['user_google_plus'])) ||
				 (false == empty($device['user_website'])) ||
				 (false == empty($device['user_tel'])) || 
				 (false == empty($device['user_email'])) )
			{
				$users[] = new User($device['user_name1'], $device['user_name2'], 
						$device['user_facebook'], $device['user_twitter'], 
						$device['user_google_plus'], $device['user_tel'],
						$device['user_email'], $device['user_website']);
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
	
	/*
	 * Returns an array of all online users
	 *
	 * @throw nothing
	 * @return array
	 */
	private function getUsersAsArray()
	{
		$jsonUsers = array();
		foreach($this->m_users as $user)
		{
			$jsonUser = array('name1' => $user->name1, 
					'name2' => $user->name2,
					'twitter' => $user->twitter,
					'facebook' => $user->facebook,
					'googlePlus' => $user->googlePlus,
					'tel' => $user->tel,
					'email' => $user->email,
					'website' => $user->website);
			array_push($jsonUsers, $jsonUser);
		}
		return $jsonUsers;
	}
	//------------------------------------------------------------------------------
	
	/**
	 * Lists all info as JSON
	 *
	 * @throw nothing
	 * @return nothing
	 */
	private function listJSON()
	{
		$output = array();
		//prepare users
		$jsonUsers = $this->getUsersAsArray();
		//append the total count nad the users to the data
		$output['data'] = array('count' => $this->m_nDevicesCount,
					'guests' => $this->m_nGuests, 
					'users' => $jsonUsers );
		return json_encode($output);
	}
	//------------------------------------------------------------------------------
	
	/*
	 * Lists all users with all guests on separate lines. Makes the life of 
	 * (lame) front-end developers easier :) The returned collection should be 
	 * convenient for Backbone.js.
	 *
	 * @throw nothing
	 * @return JSON
	 */	
	private function listUsers()
	{
		$output = array();
		//prepare users
		$jsonUsers = $this->getUsersAsArray();
		for($nIter=0;$nIter<$this->m_nGuests;$nIter++)
		{
			$jsonGuest = array('name1' => self::GUEST);
			array_push($jsonUsers, $jsonGuest);
		}
		$output['users'] = $jsonUsers;
		return json_encode($output);
	}
	//------------------------------------------------------------------------------

	private function listHTML()
	{
		$sOutput = '';
		
		// very hacking templating
		ob_start();
		include "../tmpl/header.php";
		$sOutput .= ob_get_contents();
		ob_end_clean();
	
		$sOutput .= "<h2>Online Users: ".count($this->m_users)."</h2>\n";
		$sOutput .= "<h2>Guest Devices: {$this->m_nGuests}</h2>\n";
		$sOutput .= "<h2>Total: {$this->m_nDevicesCount}</h2>\n";
		if (0 == count($this->m_users))
		{
			//No more data is available so we can terminate the method
			return $sOutput;
		}
		$sOutput .= "<h2>Users:</h2>\n";
		$sOutput .= "<ul role=\"users\">\n";
		foreach($this->m_users as $user)
		{
			// very hacky templating
			ob_start();
			include "../tmpl/user.php";
			$sOutput .= ob_get_contents();
			ob_end_clean();
		}
		$sOutput .= "</ul>\n";
		return $sOutput;
	}
	//------------------------------------------------------------------------------
	
	private function listXML()
	{
		$sOutput = '';
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
				//Google+
				$xmlGooglePlus = $xml->createAttribute('googlePlus');
				$xmlGooglePlus->value = $user->googlePlus;
				$xmlUser->appendChild($xmlGooglePlus);	
				//tel
				$xmlTel = $xml->createAttribute('tel');
				$xmlTel->value = $user->tel;
				$xmlUser->appendChild($xmlTel);
				//email
				$xmlEmail = $xml->createAttribute('email');
				$xmlEmail->value = $user->email;
				$xmlUser->appendChild($xmlEmail);
				//website
				$xmlWebsite = $xml->createAttribute('website');
				$xmlWebsite->value = $user->website;
				$xmlUser->appendChild($xmlWebsite);
				
				$xmlUsers->appendChild($xmlUser);
			}
			
			$xml->preserveWhiteSpace = false;
			$xml->formatOutput = true;
			$sOutput = $xml->saveXML();
		}
		catch (Exception $ex)
		{
			//Nothing to do
		}
		return $sOutput;
	}
	//------------------------------------------------------------------------------
	
	private function listPlainText()
	{
		$sOutput = "Online Users: ".count($this->m_users)." \n";
		$sOutput .= "Guest Devices: {$this->m_nGuests}\n";
		$sOutput .= "Total: {$this->m_nDevicesCount} \n";
		if (0 == count($this->m_users))
		{
			//exit from the function because no more data is available
			return $sOutput;
		}
		$sOutput .= "Users: \n";
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

			$sGooglePlus = $user->googlePlus;
                        if (false == empty($sGooglePlus))
                        {
                                $sOutput .= " Google+:{$user->googlePlusLink}";
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

			$sWebsite = $user->website;
                        if (false == empty($sWebsite))
                        {
                                $sOutput .= " website: {$sWebsite}";
                        }


			$sOutput .= "\n";
		}
		return $sOutput;
	}
	//------------------------------------------------------------------------------
		
}
?>
