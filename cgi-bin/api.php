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


/**
 * Take full server URI and devide it to paramas
 */
$params = explode('/',  \trim($_SERVER['REQUEST_URI'],'/'));
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
