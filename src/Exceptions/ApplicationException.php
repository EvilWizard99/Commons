<?php

namespace EWC\Commons\Exceptions;

use RuntimeException;
use Exception;

/**
 * Exception ApplicationException
 * 
 * Group Application exceptions and errors.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	RuntimeException As an exception base.
 * @uses	Exception To rethrow.
 */
class ApplicationException extends RuntimeException {
	
	/**
	 * @var	Integer Code for general or unknown issues.
	 */
	const GENERAL = 0;
	
	/**
	 * @var	Integer Code for invalid application path.
	 */
	const INVALID_APP_ROOT = 1;
	
	/**
	 * @var	Integer Code for invalid resources path.
	 */
	const INVALID_RESOURCE_ROOT = 1;
	
	/**
	 * @var	Integer Code for invalid config source.
	 */
	const INVALID_CONFIG_SOURCE = 10;
	
	/**
	 * @var	Integer Code failing to load config from source.
	 */
	const FAILED_TO_LOAD_CONFIG_SOURCE = 10;
	
	/**
	 * ApplicationException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
    public function __construct($message, $code=ApplicationException::GENERAL, Exception $previous=NULL)  {
        parent::__construct($message, $code, $previous);
    }
	
	/**
	 * Generate a failed to load config source exception.
	 * 
	 * @param	String $source_file The path to the config file attempted to be loaded.
	 * @param	String $parser_type The config parser type attempted to be used.
	 * @param	Exception $previous The caught parsing exception exception.
	 * @return	ApplicationException
	 */
	public static function withFailedToLoadConfigSource($source_file, $parser_type, Exception $previous=NULL) {
		return new static("Failed to load config source file [{$source_file}] using [{$parser_type}] parser.", static::INVALID_CONFIG_SOURCE, $previous);
	}
	
	/**
	 * Generate an application path not found exception.
	 * 
	 * @return	ApplicationException
	 */
	public static function withApplicationPathNothFound() {
		return new static("Application folder not found.", static::INVALID_APP_ROOT);
	}
	
	/**
	 * Generate an resource path not found exception.
	 * 
	 * @param	String $resource_path The path to the resource attempted to be loaded.
	 * @return	ApplicationException
	 */
	public static function withResourcePathNothFound($resource_path) {
		return new static("Resource folder [{$resource_path}] not found.", static::INVALID_RESOURCE_ROOT);
	}
	
}
