<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Library includes
 */




/**
 * Description of API
 *
 * @author ivans
 */



class API {
    
    private $config_file = "../config/config.ini.php";
    private $config;

	private $req_params;
    
    public function __construct ( $req_params = [] ){
		/*
	 	* Config file parser
		*/
		$this->config = parse_ini_file($this->config_file, true);
		$this->req_params = $req_params;
		
    }
	
	/**
	 * 
	 * 		run method is called on API object to work start execution of logic
	 * for handling api request
	 * 
	 * @return returns api response body
	 */
	public function run(){

		$args = $this->_processApi();
		
		return $this->_genResponse($args[0], $args[1]);
		
	}


	private function _processApi (){
		
		$action = $this->req_params[0];
		$arguments = array_splice( $this->req_params,1);	
		
		switch( $action ){
			case 'bla':
				//TODO do work;
				$status = "OK";
				$return_data = 'BLA BLA BLA BLA BLA';
				break;
			default:
				$status = "error";
				$return_data = "No route defined for $action. Params => $arguments";

			
		}
		
		return array($status, $return_data);
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

	$response_body = array (
	    'status' => $status,
	    'data' => $data,
	);
	return $response_body;
	
    }
    
}
