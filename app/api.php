<?php
/**
* Description of api
*
* @author Ivan Sklempej <ivan.sklempej@gmail.com>
*/

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);


/**
* Includes
*/

require_once "lib/autoloader.php";

  Autoloader::create('lib/classes.cache', array('3rdparty', '\.svn', '^images$')); 


/**
* Take full server URI and devide it to paramas
*/
$params = explode('/', \trim($_SERVER['REQUEST_URI'],'/'));
/**
* Remove first arguments from array params
*/
$_P = array_splice($params, 1);
/**
* Handler for API calls
*/
$api = new API($_P);
$response_body = $api->run();
/**
* Set header ContentType to JSON
*/
header("Content-Type: application/json");
/**
* Encode return value $rv to JSON
*/
print json_encode($response_body);
