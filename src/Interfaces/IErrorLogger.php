<?php

namespace EWC\Commons\Interfaces;

/**
 * Interface IErrorLogger
 * 
 * Define the method signatures for logging errors and exceptions.
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 */
interface IErrorLogger extends ILogger {
	
	/**
	 * Log data to the desired log handler destination.
	 * 
	 * @param	String $log_data The data / message to be logged.
	 * @param	Integer $log_type The type of log message.
	 * @param	String $log_to The error message type.
	 * @return	ILogger An instance of the object for stacking.
	 */
	public function log($log_data, $log_type, $log_to);
}
