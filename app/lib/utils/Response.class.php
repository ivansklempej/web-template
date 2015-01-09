<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Response
 *
 * @author Ivan Sklempej <ivan.sklempej@gmail.com>
 */
class Response {
	
	/**
	 *
	 * Status string
	 * 
	 * @var string
	 */
	private $status;

	/**
	 *
	 * Optional message string
	 * @var string
	 */
	private $message;
	/**
	 *
	 * Array containg data structure
	 * 
	 * @var array
	 */
	private $data;

	/**
	 * 
	 * Response construct
	 * 
	 * 
	 * @param string $status
	 * @param array $data
	 * @param string $message
	 */
	public function __construct( $status, $data, $message) {
		
		$this->status = $status;
		$this->data = $data;
		$this->message = $message;
		
	}

	/**
	 * Method returns validated response object
	 * 
	 * @return array
	 */
	public function getResponse(){
		return array( 	'status' => $this->status,
						'msg' => $this->message,
						'data' => $this->data,
					);
	}

	/**
	 * Method returns status string
	 * 
	 * @return string
	 */
	public function getStatus(){
		return $this->status;
	}


	
		
}
