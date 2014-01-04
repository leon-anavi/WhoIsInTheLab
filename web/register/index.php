<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

//include foursquare PHP API
require_once("../../classes/FourSquareManager.php");

// Load the Foursquare API library
$foursquareCtrl = new FourSquareManager();

// If the link has been clicked and the token has been received show it.
$sText = '';
if (true == isset($_GET['code']))
{
	$sToken = $foursquareCtrl->getAccessToken($_GET['code']);
	
	//get user name
	$sUserFullname = $foursquareCtrl->getName($sToken);
	$sText = "Hi, {$sUserFullname}! Your auth token is: {$sToken} <br/>\n";
	$sText .= "Send your token to info@hackafe.org\n";
}
else
{
	$sText =  "<a href='".$foursquareCtrl->getAuthenticationLink();
	$sText .=  "'>Connect to this app via Foursquare</a>";	
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>WhoIsInTheLab Registration</title>
</head>
<body>
<h1>WhoIsInTheLab Registration</h1>
<p>
<?php 
echo $sText;
?>
</p>
</body>
</html>
