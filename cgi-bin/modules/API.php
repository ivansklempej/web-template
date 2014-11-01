<?php

/**
*
* @author ivansklempej
*/
/**
 * Library includes
 */




/**
 * Description of API
 *
 * @author ivansklempej
 */



class API {
    
    private $config_file = "../config/config.ini.php";
    private $config;
    
    public function __construct (){
	/*
	 * Config file parser
	 */
	$this->config = parse_ini_file($config_file, true);
	
    }

    public function helloWorld (){
	return $this->_genResponse ( "OK", "hello world");
    }


    /**
     * 
     * _genResponse function generates array for response body
     * 
     * @param string $status status string ( eg. "OK", "NOK" )
     * @param type $data any type of data  
     * @return array  to be encoded to JSON
     */
    private function _genResponse( $status, $data){

	$response = array (
	    'status' => $status,
	    'data' => $data,
	);
	return $response;
	
    }
    
}
