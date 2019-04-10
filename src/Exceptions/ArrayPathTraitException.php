<?php

namespace EWC\Commons\Exceptions;

use RuntimeException;
use Exception;

/**
 * Exception ArrayPathTraitException
 * 
 * Group trait ArrayPath exceptions and errors.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2018 Evil Wizard Creation.
 * 
 * @uses	RuntimeException As an exception base.
 * @uses	Exception To rethrow.
 */
class ArrayPathTraitException extends RuntimeException {
	
	/**
	 * @var	Integer Code for general or unknown issues.
	 */
	const GENERAL = 70;
	
	/**
	 * @var	Integer Code for path not found in the array structure.
	 */
	const ARRAY_PATH_NOT_FOUND = 701;
	
	/**
	 * @var	Integer Code for source not being an Array.
	 */
	const BAD_SOURCE_ARRAY = 700003;
	
	/**
	 * ArrayPathTraitException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
	public function __construct($message='',$code=0, Exception $previous=NULL) {
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * Generate an array path not found exception.
	 * 
	 * @param	String $full_path The full array path.
	 * @param	String $path_section The array path section that does not exist in the structure.
	 * @return	ArrayPathTraitException
	 */
	public static function withPathNotFound($full_path, $path_section) {
		return new static("The array structure does not contain the following path section [{$path_section}] - [{$full_path}]", static::ARRAY_PATH_NOT_FOUND);
	}
	
	/**
	 * Generate bad Array source string exception.
	 * 
	 * @return	ArrayPathTraitException
	 */
	public static function withBadSourceArray() {
		return new static("Source input was not an Array.", static::BAD_SOURCE_ARRAY);
	}
	
}