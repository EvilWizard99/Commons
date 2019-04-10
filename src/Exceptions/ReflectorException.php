<?php

namespace EWC\Commons\Exceptions;

use RuntimeException;
use Exception;
use ReflectionException;

/**
 * Exception ReflectorException
 * 
 * Group Config exceptions and errors.
 *
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	RuntimeException As an exception base.
 * @uses	Exception To rethrow.
 * @uses	ReflectionException Named exception.
 */
class ReflectorException extends RuntimeException {
	
	/**
	 * @var	Integer Code for general or unknown issues.
	 */
	const GENERAL = 0;
	
	/**
	 * @var	Integer Code for failing to get a reflection of the class.
	 */
	const REFLECTION_FAILED = 1;
	
	/**
	 * @var	Integer Code for failing to get a reflection of the class.
	 */
	const REFLECTIED_INSTANCE_FAILED = 2;
	
	/**
	 * @var	Integer Code for class not having the correct inheritance type.
	 */
	const REFLECTION_TYPE_MISMATCH = 3;
	
	/**
	 * @var	Integer Code for calling an illegal method.
	 */
	const ILLEGAL_METHOD_CALL = 20;
	
	/**
	 * @var	Integer Code for calling a method without enough required parameters.
	 */
	const ARGUMENT_COUNT_MISMATCH = 21;
	
	/**
	 * ReflectorException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
    public function __construct($message, $code=ReflectorException::CODE_GENERAL, Exception $previous=NULL)  {
        parent::__construct($message, $code, $previous);
    }
	
	/**
	 * Generate a failed to get a reflection of the class exception.
	 * 
	 * @param	String $classname The fully qualified class name of the object being reflected.
	 * @param	ReflectionException $previous The caught reflection exception exception.
	 * @return	ReflectorException
	 */
	public static function withReflectionFailed($classname, ReflectionException $previous) {
		return new static("Unable to get reflection of object [{$classname}].", static::REFLECTION_FAILED, $previous);
	}
	
	/**
	 * Generate a failed to get a reflected instance of the class exception.
	 * 
	 * @param	String $classname The fully qualified class name of the object being reflected.
	 * @param	ReflectionException $previous The caught reflection exception exception.
	 * @return	ReflectorException
	 */
	public static function withFailedToGetInstance($classname, ReflectionException $previous) {
		return new static("Unable to get reflected instance of object [{$classname}].", static::REFLECTIED_INSTANCE_FAILED, $previous);
	}
	
	/**
	 * Generate an illegal method call exception.
	 * 
	 * @param	String $method_name The method name attempted to be called.
	 * @return	ReflectorException
	 */
	public static function withIllegalMethodCall($method_name) {
		return new static("Calling method [{$method_name}] directly is not permitted.", static::ILLEGAL_METHOD_CALL);
	}
	
	/**
	 * Generate a method argument count mismatch exception.
	 * 
	 * @param	String $classname The fully qualified class name of the object being reflected.
	 * @param	String $method_name The method name attempted to be called.
	 * @param	Integer $required The required number of parameters for the method.
	 * @param	Integer $supplied The supplied number of parameters for the method.
	 * @return	ReflectorException
	 */
	public static function withArgumentCountMismatch($classname, $method_name, $required, $supplied) {
		return new static("{$classname}::{$method_name}() expects {$required} parameters, {$supplied} given", static::ARGUMENT_COUNT_MISMATCH);
	}
	
	/**
	 * Generate a method argument count mismatch exception.
	 * 
	 * @param	String $classname The fully qualified class name of the object being reflected.
	 * @param	String $type The fully qualified class name of the object type checked as.
	 * @return	ReflectorException
	 */
	public static function withTypeMismatch($classname, $type) {
		return new static("{$classname} does not match expected type [{$type}]", static::REFLECTION_TYPE_MISMATCH);
	}

}
