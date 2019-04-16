<?php

namespace EWC\Commons\Errors;

use Exception;

/**
 * Class ContextError
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	Exception As a refference to the actual error occurance.
 */
class ContextError {
	
	/**
	 * @var Exception A reflection of the object.
	 */
	protected $exception = NULL;
	
	/**
	 * ContextError constructor.
	 *
	 * @param 	Exception $exception Optional caught exception.
	 */
	public function __construct(Exception $exception=NULL) {
		if(is_null($exception)) { $exception = new Exception("No Exception Context Set"); }
		// set the caught exception context
		$this->exception = $exception;
	}
	
	/**
	 * Return a refference to the caught exception.
	 * 
	 * @return	Exception A refference to the caught exception.
	 */
	public function &getException() { return $this->exception; }
	
}
