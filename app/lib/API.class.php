<?php

/**
 * Summary for file
 * @filesource
 */

/**
 * Main class for handling API requests.
 * 
 * bla bla bla
 *
 *
 *
 * @author Ivan Sklempej <ivan.sklempej@gmail.com>
 *
 * @package API
 */
class API {

	/**
	 *
	 * Path to general configuration file
	 *
	 * @var string
	 */
	private $config_file = "config/config.ini.php";

	/**
	 * Configuration object
	 *
	 * Config object parsed from $config_file ini file
	 *
	 * @var object
	 */
	protected $config = null;

	/**
	 *
	 * Request arguments
	 *
	 * Array containing request arguments from exploding request_uri in api.php
	 *
	 * @var array
	 */
	private $req_args = null;

	/**
	 *
	 * Construct
	 *
	 * Initialization of config file and request arguments.
	 *  Config file is parsed as ini file with ini structure taken in consideration
	 *
	 * @param array $req_args Description
	 *
	 */
	public function __construct( $req_args = array() ) {
		$this->config = parse_ini_file($this->config_file, true);
		$this->req_args = $req_args;
	}

	/**
	 * Run
	 *
	 * method is called on API object to start execution of logic for handling api request
	 *
	 * @return array Api response body.
	 */
	public function run() {
		$return = $this->_processApi();
		return $return->getResponse();
	}

	/**
	 * Process API request
	 *
	 * method initializes main class for called module and runs module
	 *
	 * @return array array [string $status, string $message, array $return_data]
	 */
	private function _processApi() {
		$action = $this->req_args[0];
		$arguments = array_splice($this->req_args, 1);
		
		$x_method = filter_input( INPUT_SERVER, "REQUEST_METHOD" );

		
		if (class_exists($action)){
		
			$obj = new $action( $x_method );
			$return = $obj->run( $arguments );
			
		} else {
			$status = "error";
			$message = "No route defined for $action";
			$data = $arguments;
			$return = new Response($status, $data, $message);
		}
		return $return;
		
	}


}
