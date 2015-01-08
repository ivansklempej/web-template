<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bla
 *
 * @author ivans
 */
class example {

	//put your code here

	private $x_method;

	public function __construct($x_method) {
		$this->x_method = $x_method;
	}

	public function run($arguments) {

		switch ($this->x_method) {
			case 'PUT':
				break;
			case 'POST':
				break;
			case 'GET':
				break;
			case 'HEAD':
				break;
			case 'DELETE':
				break;
			case 'OPTIONS':
				break;
			default:
				break;
		}

		return "BLA BLA BLA";
	}

}
