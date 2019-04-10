<?php

namespace EWC\Commons\Exceptions;

use RuntimeException;
use Exception;

/**
 * Exception AuthenticationException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	RuntimeException As an exception base.
 * @uses	Exception To rethrow.
 */
class AuthenticationException extends RuntimeException {

	/**
	 * AuthenticationException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
	public function __construct($message = '', $code = 0, Exception $previous = NULL) {
		parent::__construct($message, $code, $previous);
	}

}
