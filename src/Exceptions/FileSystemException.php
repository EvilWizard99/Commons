<?php

namespace EWC\Commons\Exceptions;

use RuntimeException;
use Exception;

/**
 * Exception FileSystemException
 * 
 * @version 1.0.0
 * @author Russell Nash <evil.wizard95@googlemail.com>
 * @copyright 2019 Evil Wizard Creation.
 * 
 * @uses	RuntimeException As an exception base.
 * @uses	Exception To rethrow.
 */
class FileSystemException extends RuntimeException {
	
	/**
	 * @var	Integer Code for file not found.
	 */
	const FILE_NOT_FOUND = 10;
	
	/**
	 * @var	Integer Code for failing to make a directory.
	 */
	const MAKE_DIR_FAILED = 20;
	
	/**
	 * @var	Integer Code for failing to open a file for writing.
	 */
	const OPEN_FOR_WRITE = 200;
	
	/**
	 * @var	Integer Code for failing to write the file contents.
	 */
	const WRITE_FILE = 210;
	
	/**
	 * @var	Integer Code for overwriting file contents not allowed.
	 */
	const OVERWRITE_FILE_DISALLOWED = 220;

	/**
	 * FileSystemException constructor.
	 * 
	 * @param	String $message An error message for the exception.
	 * @param	Integer $code An error code for the exception.
	 * @param	Exception $previous An optional previously thrown exception.
	 */
	public function __construct($message='', $code=0, Exception $previous=NULL) {
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 * Generate a file not found exception.
	 * 
	 * @param	String $file The name / path to the file not found.
	 * @return	FileSystemException
	 */
	public static function withFileNotFound($file) {
		return new static("File [{$file}] not found.", static::FILE_NOT_FOUND);
	}
	
	/**
	 * Generate a failed to make a directory exception.
	 * 
	 * @param	String $folder_name The name / path to the folder to create.
	 * @return	FileSystemException
	 */
	public static function withMakeDirFailed($folder_name) {
		return new static("Failed to make directory [{$folder_name}].", static::MAKE_DIR_FAILED);
	}
	
	/**
	 * Generate a failed to open a file for writing exception.
	 * 
	 * @param	String $file The name / path to the file not able to be opened for writing.
	 * @return	FileSystemException
	 */
	public static function withWriteFileFailed($file) {
		return new static("Unable to open [{$file}] for writing.", static::OPEN_FOR_WRITE);
	}
	
	/**
	 * Generate a failed to write the file contents exception.
	 * 
	 * @param	String $file The name / path to the file not able to write the contents to.
	 * @return	FileSystemException
	 */
	public static function withWriteFileContentsFailed($file) {
		return new static("Unable to write file contents for [{$file}].", static::WRITE_FILE);
	}
	
	/**
	 * Generate a failed to write the file contents exception.
	 * 
	 * @param	String $file The name / path to the file not able to write the contents to.
	 * @return	FileSystemException
	 */
	public static function withOverwriteFileContentsDisallowed($file) {
		return new static("Unable to overwrite file [{$file}].", static::OVERWRITE_FILE_DISALLOWED);
	}

}
