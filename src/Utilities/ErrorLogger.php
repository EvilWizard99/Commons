<?php

namespace EWC\Commons\Utilities;

use Exception;
use DateTime;

/**
 * Class ErrorLogger
 * 
 * Act as central handler for logging errors, warnings, notices, exceptions etc. to various output streams.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2017 Evil Wizard Creation.
 * 
 * @uses	Exception For type casting exception extended object parameters.
 * @uses	ErrorLogData To wrap data into a compatible format to be logged to file.
 * @uses	DateTime To date time details to the log entry for data logged to file.
 */
class ErrorLogger {
	
	/**
	 * @var	Integer The log type is a general message.
	 */
	const TYPE_GENERAL = 0;
	
	/**
	 * @var	Integer The log type is a notice message.
	 */
	const TYPE_NOTICE = 1;
	
	/**
	 * @var	Integer The log type is a warning message.
	 */
	const TYPE_WARNING = 2;
	
	/**
	 * @var	Integer The log type is a error message.
	 */
	const TYPE_ERROR = 3;
	
	/**
	 * @var	Integer The log type is a exception.
	 */
	const TYPE_EXCEPTION = 4;
	
	/**
	 * @var	Integer The log type is data output.
	 */
	const TYPE_DATA = 5;
	
	/**
	 * @var	Integer Log the error message using the OS system log.
	 */
	const LOG_TO_SYSTEM = 0;
	
	/**
	 * @var	Integer Log the error message to the specified system email address.
	 */
	const LOG_TO_EMAIL = 1;
	
	/**
	 * @var	Integer Log the error message to a file.
	 */
	const LOG_TO_FILE = 3;
	
	/**
	 * @var	Integer Log the error message using the SAPI logging handler.
	 */
	const LOG_TO_SAPI = 4;
	
	/**
	 * @var	ErrorLogger The object singleton instance reference.
	 */
	protected static $oMyInstance = NULL;
	
	/**
	 * @var	String The base path to the custom log files.
	 */
	protected $custom_log_to_path;
	
	/**
	 * @var	Array List of logger types.
	 */
	protected $loggers = [];
	
	/**
	 * @var	String Ensure the loggers array index is a string.
	 */
	protected $logger_index_key = "l-";
	
	/**
	 * @var	String A code to be added to log output for use with grep in logs.
	 */
	protected $grep_code = NULL;
	
	/**
	 * @var	String Hold the grep code when using a temporary override code.
	 */
	protected $temp_grep_code = NULL;
	
	/**
	 * @var	Boolean Flag to indicate the grep code is temporary overridden.
	 */
	protected $using_temp_grep_code = FALSE;
	
	/**
	 * @var	Array List of email headers to be used when logging to email.
	 */
	protected $email_headers = [];
	
	/**
	 * ErrorLogger constructor.
	 */
	protected function __construct() {
		$this->custom_log_to_path = APP_ROOT . "/logs/";
		$loggers = [
			static::TYPE_GENERAL		=> "general",
			static::TYPE_NOTICE		=> "notice",
			static::TYPE_WARNING	=> "warning",
			static::TYPE_ERROR		=> "error",
			static::TYPE_EXCEPTION	=> "exception",
			static::TYPE_DATA		=> "data"
		];
		// setup the logger types basic definitions
		foreach($loggers as $logger_type => $logger_name) {
			$this->setupLogger($logger_type, $logger_name);
		}
	}
	
	/**
	 * This object should be treated as a singleton instance.
	 * 
	 * @return	ErrorLogger The instance reference.
	 */
	public static function getInstance() {
		if(is_null(static::$oMyInstance) || !(static::$oMyInstance instanceof ErrorLogger)) {
			static::$oMyInstance = new static();
		}
		return static::$oMyInstance;
	}
	
	/**
	 * Set a code that can be used with grep in the logs to group log entries.
	 * 
	 * @param	String $code The code to be added to the log entries.
	 * @param	Boolean $temp Flag to indicate the grep code should be only used for the next log action.
	 * @return	ErrorLogger An instance of the object for stacking.
	 */
	public function &setGrepCode($code, $temp=FALSE) { 
		if($temp) {
		// set the temporary override of the grep code
			$this->temp_grep_code = $this->grep_code;
			$this->using_temp_grep_code = TRUE;
		}
		$this->grep_code = $code;
		return $this;
	}
	
	/**
	 * Turn off the use of the grep code prefix to log messages.
	 */
	public function unsetGrepCode() { $this->grep_code = NULL; }
	
	/**
	 * Log data to the desired log handler destination.
	 * 
	 * @param	String $log_data The data / message to be logged.
	 * @param	Integer $log_type The type of log message.
	 * @param	String $log_to The error message type.
	 * @return	ErrorLogger An instance of the object for stacking.
	 */
	public function log($log_data, $log_type=ErrorLogger::TYPE_GENERAL, $log_to=ErrorLogger::LOG_TO_SYSTEM) {
		$this->logTo($log_to, $log_data, $log_type);
		return $this;
	}
	
	/**
	 * Log exception details.
	 * 
	 * @param	Exception $ex The Exception to be logged.
	 * @param	Boolean $log_to_system Flag to indicate the exception should also be logged to the system.
	 * @return	ErrorLogger An instance of the object for stacking.
	 */
	public function logException(Exception $ex, $log_to_system=TRUE) {
		// determine where the exception was caused
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		// create the exception log data
		$oExData = [
			"Exception: {$ex->getMessage()}",
			"Code: {$ex->getCode()}",
			"File: {$ex->getFile()}",
			"Line: {$ex->getLine()}",
			"Called from: {$backtrace[1]["function"]}()",
			"Trace:\n" . $ex->getTraceAsString()
		];
		// log the exception data
		$this->logData($oExData, get_class($ex) . " Exception Handled", ErrorLogger::TYPE_EXCEPTION);
//		$this->logTo(ErrorLogger::LOG_TO_FILE, $oExData, ErrorLogger::TYPE_EXCEPTION);
		if($log_to_system) {
		// also log a shorthand version of the exception to the standard system error log
			$log_message = "Exception: {$ex->getMessage()}";
			$this->logTo(ErrorLogger::LOG_TO_SYSTEM, $log_message, ErrorLogger::TYPE_EXCEPTION);
		}
		return $this;
	}
	
	/**
	 * Log data to file.
	 * 
	 * @param	String $log_data The data / message to be logged.
	 * @param	String $data_name A name to identify what the logged data is / related to.
	 * @param	Integer $log_type The type of log message.
	 * @return	ErrorLogger An instance of the object for stacking.
	 */
	public function logData($log_data, $data_name, $log_type=ErrorLogger::TYPE_GENERAL) {
		// create the log data object
		$oData = new ErrorLogData($data_name, $log_data);
		$this->logTo(ErrorLogger::LOG_TO_FILE, $oData, $log_type);
		return $this;
	}
	
	/**
	 * Log the data to the desired destination.
	 * 
	 * @param	String $log_to The error message type.
	 * @param	String $log_data The data / message to be logged.
	 * @param	Integer $log_type The type of log message.
	 */
	protected function logTo($log_to, $log_data, $log_type) {
		switch($log_to) {
			case static::LOG_TO_EMAIL :
			// email the log entry to the server admin
				error_log($log_data, $log_to, "root@localhost", implode("\r\n", $this->email_headers));
			break;
			case static::LOG_TO_FILE :
			// log the entry to the message type file destination
				$this->logDataToFile($log_data, $log_type);
			break;
			case static::LOG_TO_SAPI :
			case static::LOG_TO_SYSTEM :
			default :
			// log the message to the standard log handler
				$this->addGrepCode($log_data);
				error_log($log_data, $log_to);
			break;
		}		
		if($this->using_temp_grep_code) {
		// restore grep code after the temporary override
			$this->grep_code = $this->temp_grep_code;
			$this->using_temp_grep_code = FALSE;
		}
	}
	
	/**
	 * Log the data to file.
	 * 
	 * @param	ErrorLogData $log_data The data to be logged.
	 * @param	Integer $data_type The data type being logged to file.
	 */
	protected function logDataToFile(ErrorLogData $log_data, $data_type) {
		// determine what is running the script
		$log_of = (array_key_exists("SERVER_NAME", $_SERVER)) ? $_SERVER["SERVER_NAME"] : "cli";
		// get the file path to store the data in for the log type
		$log_file = $this->custom_log_to_path . sprintf($this->loggers["{$this->logger_index_key}{$data_type}"]["file"], $log_of);
		// prefix the data log entry with the date and time
		$oDate = new DateTime();
		$log = $oDate->format("[D M d H:i:s.u Y]") . ' ' . $log_data->__toString();
		// use the error log to log data to the specified file
		error_log($log, ErrorLogger::LOG_TO_FILE, $log_file);
	}
	
	/**
	 * Setup common values for the logger types.
	 * 
	 * @param	Integer $logger_type The logger type ID.
	 * @param	String $logger_name The name of the logger.
	 */
	protected function setupLogger($logger_type, $logger_name) {
		$this->loggers["{$this->logger_index_key}{$logger_type}"] = [
			"file"	=> "%s-{$logger_name}_log"
		];
	}
	
	/**
	 * Add the searchable grep code to the log message if needed.
	 * 
	 * @param	String $log_message Pass-by-reference of the message to be logged.
	 */
	protected function addGrepCode(&$log_message) {
		if(!is_null($this->grep_code)) {
			$log_message = "[{$this->grep_code}] {$log_message}";
		}
	}
}
