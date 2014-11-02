<?php

/**
* Description of api
*
* @author ivansklempej
*/
/**
* Includes
*/

require_once "modules/API.php";


$action = $_GET['action'];

/**
* Handler for API calls
*/


switch ($action){

	case 'hello':
		$api = new API();
		$rv = $api->helloWorld();
		break;
    case 'greeting':
        $user_name = $_POST['user_name'];
        $rv = $api->greetings($user_name);
	default:
		$rv = array( 'status' => "NOK", 'message' => "Route not recognized!");


}

/**
* Set header ContentType to JSON
*/
header("Content-Type: application/json");

/**
* Encode return value $rv to JSON
*/
print json_encode($rv);


