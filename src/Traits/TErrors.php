<?php

namespace EWC\Commons\Traits;

use EWC\Commons\Utilities\ErrorLogger;
use EWC\Commons\Interfaces\ILogger;
use Exception;

/**
 * Trait TErrors
 * 
 * Define the method functionality of adding and retrieving errors.
 * 
 * Provides the following methods for public access.
 * 
 * getLastError()
 * getErrors()
 * 
 * Provides the following methods for protected access.
 * 
 * addError($error, $trigger=FALSE)
 * logException(Exception $ex)
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2017 Evil Wizard Creation.
 */
trait TErrors {
	
	private $_traiat_errorLogger;
	
	/**
	 * @var	Array List of errors encountered.
	 */
	protected $_traiat_errors = [];
	
	/**
	 * Get the last error encountered.
	 * 
	 * @return	Mixed The last error encountered.
	 */
	public function getLastError() { return end($this->_traiat_errors); }
	
	/**
	 * Get the errors encountered so far.
	 * 
	 * @param	Array The errors encountered.
	 */
	public function getErrors() { return $this->_traiat_errors; }
	
	public function registerErrorLogger(ILogger $errorLogger) { $this->_traiat_errorLogger = $errorLogger; }
	
	/**
	 * Add / log an encountered error.
	 * 
	 * @param	Mixed $error The error encountered.
	 * @param	Boolean Flag to indicate the error should be triggered.
	 */
	protected function addError($error, $trigger=FALSE) { 
		$this->_traiat_errors[] = $error;
		if($trigger) {
			trigger_error($error);
		}
	}
	
	/**
	 * Log exception details using the log handler.
	 * 
	 * @param	Exception The exception to log the details.
	 */
	protected function logException(Exception $ex) { $this->_traiat_errorLogger->logException($ex, TRUE); }
	
}
