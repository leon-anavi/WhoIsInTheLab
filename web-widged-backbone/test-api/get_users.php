<?php
/***
 * NOTE: This shloud be used until JSONP support is implemented for cross domain communication
 */
$response = file_get_contents("http://87.97.198.36/who/api.php?format=json");
echo $response;